<?php

Route::filter('login_check',function()
{
    session_start();

    if(empty($_SESSION['user_id'])){

        if(App::environment('local'))
            return Redirect::to('http://localhost/makeadiff.in/home/makeadiff/public_html/madapp/index.php/auth/login/' . base64_encode(Request::url()));
        else
            return Redirect::to('http://makeadiff.in/madapp/index.php/auth/login/' . base64_encode(Request::url()));

    }


});

Route::filter('propel_check',function(){

    if(!HomeController::checkPropel())
        return Redirect::to('error')->with('message','Only Propel Fellows and Propel Wingmen can access the Propel App');

});

Route::get('/error','CommonController@showError');

Route::group(array('before'=>'login_check|propel_check'),function()
{
    Route::get('/','HomeController@showHome');
    Route::get('/success','CommonController@showSuccess');

    Route::get('/wingman-journal/select-wingman','WingmanJournalController@selectWingman');
    Route::get('/wingman-journal/{wingman_id}','WingmanJournalController@showList');

    Route::get('/calendar/select-wingman','CalendarController@selectWingman');
    Route::get('/calendar/{wingman_id}','CalendarController@showStudents');
    Route::get('/calendar/{wingman_id}/{student_id}','CalendarController@showCalendar');
    Route::post('/calendar/createEdit','CalendarController@createEdit');
    Route::post('/calendar/cancelEvent','CalendarController@cancelEvent');
    Route::post('/calendar/approve','CalendarController@approveEvents');
    Route::resource('/journal-entry','JournalEntryController',array('except' => array('index')));

    Route::get('/attendance/select-wingman','AttendanceController@selectWingman');
    Route::get('/attendance/{user_id}','AttendanceController@show');
    Route::post('/attendance/{user_id}','AttendanceController@save');

    Route::get('/settings/subjects','SettingController@selectSubjects');
    Route::post('/settings/subjects','SettingController@saveSubjects');
    Route::get('/settings/wingmen','SettingController@selectWingmen');
    Route::post('/settings/wingmen','SettingController@saveWingmen');

    Route::get('/settings/students','SettingController@selectStudents');
    Route::post('/settings/students','SettingController@saveStudents');

    Route::get('/reports/class-status/select-city','ReportController@showCities');
    Route::get('/reports','ReportController@showReports');


});
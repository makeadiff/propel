<?php

Route::filter('login_check',function()
{
    session_start();

    //$_SESSION['user_id']=48032; //48032 //47642 //22730 //50671

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
    Route::get('/',['as'=>'home','uses'=>'HomeController@showHome']);
    Route::get('/success','CommonController@showSuccess');
    Route::get('/logout','CommonController@logout');

    Route::get('/wingman-journal/select-wingman','WingmanJournalController@selectWingman');
    Route::get('/wingman-journal/{wingman_id}','WingmanJournalController@showList');

    Route::get('/feedback/module-feedback','WingmanJournalController@showModuleFeedback');
    Route::get('/feedback/select-student','WingmanJournalController@selectStudentsCity');
    Route::get('/feedback/{wingman_id}','WingmanJournalController@showStudents');
    Route::get('/feedback/{wingman_id}/{student_id}','WingmanJournalController@showFeedback');
    
    Route::get('/calendar/select-wingman','CalendarController@selectWingman');
    Route::get('/calendar/approve-calendar','CalendarController@approveView');
    Route::get('/calendar/select-center','CalendarController@selectCenter');
    Route::get('/calendar/center/{center_id}','CalendarController@showCenterCalendar');
    Route::get('/calendar/select-asv','CalendarController@selectAsv');
    Route::get('/calendar/asv/{asv_id}','CalendarController@showAsvCalendar');
    Route::get('/calendar/{wingman_id}','CalendarController@showStudents');
    Route::get('/calendar/{wingman_id}/{student_id}','CalendarController@showCalendar');
    Route::post('/calendar/createEvent','CalendarController@createEvents');
    Route::post('/calendar/editEvent','CalendarController@editEvent');
    Route::post('/calendar/cancelEvent','CalendarController@cancelEvent');
    Route::get('/calendar/approve/{student_id}/{month}/{year}','CalendarController@approve');
    Route::post('/calendar/bulk-approve','CalendarController@approveSelected');

    Route::resource('/journal-entry','JournalEntryController',array('except' => array('index')));
    
    Route::get('/attendance/select-wingman','AttendanceController@selectWingman');
    Route::get('/attendance/{user_id}','AttendanceController@show');
    Route::post('/attendance/{user_id}','AttendanceController@save');

    Route::get('/settings/subjects','SettingController@selectSubjects');
    Route::post('/settings/subjects','SettingController@saveSubjects');
    Route::get('/settings/wingmen','SettingController@selectWingmen');
    Route::post('/settings/wingmen','SettingController@saveWingmen');

    Route::get('/settings/select-wingman','SettingController@selectWingman');

    Route::get('/settings/students','SettingController@selectStudents');
    Route::post('/settings/students','SettingController@saveStudents');

    Route::get('/settings/{wingman_id}/students','SettingController@selectWingmanStudents');
    Route::post('/settings/{wingman_id}/students','SettingController@saveWingmanStudents');

    Route::get('/reports/class-status/select-city','ReportController@showCities');
    Route::get('/reports','ReportController@showReports');
    Route::get('/reports/class-status/city/{city_id}','ReportController@showClassStatus');
    Route::get('/reports/wingman-journal-report','ReportController@showWingmanJournalReport');
    Route::get('/reports/attendance-report','ReportController@showAttendanceReport');

    Route::get('/city-change/city-select','CityChangeController@showCitySelect');
    Route::get('/city-change/city/{city_id}','CityChangeController@showWingmanSelect');
    Route::get('/city-change/wingman/{wingman_id}','CityChangeController@changeToWingman');
    Route::get('/city-change/back-to-national','CityChangeController@backToNational');

});
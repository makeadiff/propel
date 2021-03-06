<?php

Route::filter('login_check',function()
{
    session_start();
    // $_SESSION['user_id']= 57184; //73673;//34752;
    // 73673; //124520; //26956; //62841; //78268;//105354;//36327;//11752; //66804; //48032 //47642 //22730 //50671 //48286 //85896

    if(empty($_SESSION['user_id'])){
       if(App::environment('local'))
            return Redirect::to('http://127.0.0.1/makeadiff/madapp/index.php/auth/login/' . base64_encode(Request::url()));
        else
            return Redirect::to('http://makeadiff.in/madapp/index.php/auth/login/' . base64_encode(Request::url()));

    }
});

Route::filter('propel_check',function(){

    if(!HomeController::checkPropel())
        return Redirect::to('error')->with('message','Only Propel Fellows and Propel Wingmen can access the Propel App');

});

Route::get('/error','CommonController@showError');
Route::get('/reminders','CalendarController@eventreminder');

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
    Route::get('/calendar/all-wingman','CalendarController@showAllWingman');
    Route::get('/calendar/wingman/{wingman_id}','CalendarController@showWingmanCalendar');
    Route::get('/calendar/approve-calendar','CalendarController@approveView');
    Route::get('/calendar/select-center','CalendarController@selectCenter');
    Route::get('/calendar/center/{center_id}','CalendarController@showCenterCalendar');
    Route::get('/calendar/select-asv','CalendarController@selectAsv');
    Route::get('/calendar/asv/{asv_id}','CalendarController@showAsvCalendar');
    Route::get('/calendar/{wingman_id}','CalendarController@showStudents');
    Route::get('/calendar/{wingman_id}/{student_id}','CalendarController@showCalendar');
    Route::post('/calendar/createEvent','CalendarController@createEvent');
    Route::post('/calendar/asv/createEvent','CalendarController@createEvent');
    Route::post('/calendar/editEvent','CalendarController@editEvent');
    Route::post('/calendar/rescheduleEvent','CalendarController@rescheduleEvent');
    Route::post('/calendar/cancelEvent','CalendarController@cancelEvent');
    Route::post('/calendar/asv/cancelEvent','CalendarController@cancelEvent');


    Route::get('/calendar/approve/{student_id}/{month}/{year}','CalendarController@approve');
    Route::post('/calendar/bulk-approve','CalendarController@approveSelected');

    Route::resource('/journal-entry','JournalEntryController',array('except' => array('index')));

    Route::get('/attendance/select-profile','AttendanceController@selectProfile');
    Route::get('/attendance/select-wingman','AttendanceController@selectWingman');
    Route::get('/attendance/asv/{timeline?}','AttendanceController@selectASV');
    // Route::get('/attendance/wingmen/{timeline?}','AttendanceController@selectWingmen');
    Route::get('/attendance/wingman/{timeline?}','AttendanceController@showAttendanceToFellow');
    Route::get('/attendance/{user_id}','AttendanceController@showAttendanceToWingman');
    Route::post('/attendance/{user_id}','AttendanceController@save');
    Route::post('/attendance/wingman/{user_id}','AttendanceController@save');
    Route::post('/attendance/asv/{timeline?}','AttendanceController@save');
    Route::post('/attendance/{user_id}','AttendanceController@save');
    Route::post('/attendance/wingman/{user_id}/previous','AttendanceController@save');

    Route::get('/settings/subjects','SettingController@selectSubjects');
    Route::post('/settings/subjects','SettingController@saveSubjects');
    Route::get('/settings/wingmen','SettingController@selectWingmen');
    Route::post('/settings/wingmen','SettingController@saveWingmen');

    Route::get('/settings/select-wingman','SettingController@selectWingman');

    Route::get('/profile/{student_id}','ProfileController@childProfileIndex');
    Route::get('/modules/{student_id}','ProfileController@childWingmanModules');

    Route::get('/settings/{wingman_id}/students','SettingController@selectStudents');
    Route::post('/settings/{wingman_id}/students','SettingController@saveStudents');

    Route::get('/reports','ReportController@showReports');
    Route::get('/reports-filter/{start?}/{end?}','ReportController@showReports');
    Route::get('/reports/class-status/select-city','ReportController@showCities');
    Route::get('/reports/class-status/city/{city_id}','ReportController@showClassStatus');
    Route::get('/reports/wingman-journal-report','ReportController@showWingmanJournalReport');
    Route::get('/reports/attendance','ReportController@attendanceHome');
    Route::post('/reports/attendanceReport','ReportController@attendanceReport');
    Route::get('/reports/attendance-report/{city_id?}/{type?}/{start_date?}/{end_date?}','ReportController@showAttendanceReport');

    Route::get('/reports/class-cancelled-report','ReportController@showCancellationReport');
    Route::post('/reports/class-cancelled-report','ReportController@CancellationFilter');

    Route::get('/reports/child-report','ReportController@showChildReport');
    Route::get('/reports/child-report/{city_id}/{start_date?}/{end_date?}','ReportController@showCityReport');
    Route::post('/reports/child-report/city-report','ReportController@showCityReportForm');
    Route::get('/reports/child-report/{city_id}/{center_id}','ReportController@showCenterReport');

    Route::get('reports/calendar-summary','ReportController@calendarSummary');
    Route::get('reports/asv-calendar-summary/{city_id?}/{start_date?}/{end_date?}','ReportController@ASVcalendarSummary');
    Route::get('/reports/calendar-approval','CalendarController@calendarApproval');
    Route::post('/reports/calendar-approval','CalendarController@calendarApproval');
    Route::post('/reports/calendarApproval/','CalendarController@calendarFilter');
    Route::post('/reports/city-calendar','CalendarController@calendarFilter');
    Route::get('/reports/calendar-approval/{city_id?}/{start_date?}/{end_date?}','CalendarController@calendarApproval');

    Route::get('/city-change/city-select','CityChangeController@showCitySelect');
    Route::get('/city-change/city/{city_id}','CityChangeController@showFellowSelect');
    Route::get('/city-change/fellow/{fellow_id}','CityChangeController@changetoFellow');
    Route::get('/city-change/back-to-national','CityChangeController@backToNational');

});

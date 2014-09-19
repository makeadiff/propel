<?php

Route::filter('login_check',function()
{
    session_start();

    if(empty($_SESSION['user_id'])){

        if(App::environment('local'))
            return Redirect::to('http://localhost/makeadiff.in/home/makeadiff/public_html/apps/set_session_test.php?url=' . base64_encode(Request::url()));
        else
            return Redirect::to('http://makeadiff.in/madapp/index.php/auth/login/' . base64_encode(Request::url()));

    }


});

Route::group(array('before'=>'login_check'),function()
{
    Route::get('/','HomeController@showHome');
    Route::get('/success','CommonController@showSuccess');
    Route::get('/error','CommonController@showError');
    Route::get('/wingman-journal/{user_id}','WingmanJournalController@showList');
    Route::resource('/journal-entry','JournalEntryController',array('except' => array('index')));

});
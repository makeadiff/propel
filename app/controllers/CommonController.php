<?php

class CommonController extends BaseController
{


    public function showSuccess()
    {
        return View::make('success');
    }

    public function showError()
    {
        session_start();
        return View::make('error');
    }

    public function logout()
    {
        unset($_SESSION['original_id']);

        if(App::environment('local')) {
            return Redirect::to("http://localhost/makeadiff.in/home/makeadiff/public_html/madapp/index.php/auth/logout");
        } else {
            return Redirect::to("http://makeadiff.in/madapp/index.php/auth/logout");
        }

    }


}

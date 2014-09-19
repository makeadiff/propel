<?php

class CommonController extends BaseController
{


    public function showSuccess()
    {
        return View::make('success');
    }

    public function showError()
    {
        return View::make('error');
    }

}

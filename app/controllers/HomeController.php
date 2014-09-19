<?php

class HomeController extends BaseController
{


	public function showHome()
	{
        $user_id = $_SESSION['user_id'];
        return View::make('home')->with('user_id',$user_id);
    }


}

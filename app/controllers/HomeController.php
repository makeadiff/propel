<?php

class HomeController extends BaseController
{

    public static function checkPropel(){
        $user_id = $_SESSION['user_id'];

        $user = Volunteer::find($user_id);

        $groups = $user->group()->get();

        $flag = false;


        foreach($groups as $group) {
            if($group->id == '272' || $group->id == '348' || $group->id == '359' || $group->id == '212' || $group->id == '365') {
                $flag = true;
            }

        }

        if($flag == true)
            return true;
        else
            return false;
    }

    public static function setGroup()
    {
        $user_id = $_SESSION['user_id'];

        $user = Volunteer::find($user_id);

        $groups = $user->group()->get();

        $fellow = false;
        $wingman = false;
        $after_care_wingman = false;
        $strat = false;
        $director = false;

        foreach($groups as $group) {
            if($group->id == '272')
                $fellow = true;
            elseif($group->id == '348')
                $wingman = true;
            elseif($group->id == '359')
                $strat = true;
            elseif($group->id == '365')
                $after_care_wingman = true;
            elseif($group->id == '212'){
                $director = true;
                $_SESSION['original_id'] = $_SESSION['user_id'];
            }

        }
        if($director == true)
            View::share('user_group','Program Director, Propel');
        elseif($strat == true)
            View::share('user_group','Propel Strat');
        elseif($fellow == true)
            View::share('user_group','Propel Fellow');
        elseif($wingman == true)
            View::share('user_group','Propel Wingman');
        elseif($after_care_wingman == true)
            View::share('user_group','After Care Wingman');

    }


	public function showHome()
	{
        $user_id = $_SESSION['user_id'];

        $this->setGroup();

        $user = Volunteer::find($user_id);

        return View::make('home')->with('user',$user);
    }


}

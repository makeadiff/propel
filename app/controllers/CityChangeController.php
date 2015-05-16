<?php

Class CityChangeController extends BaseController
{
    public function showCitySelect() {
        $cities = City::orderBy('name','asc')->get();
        return View::make('city.city-select')->with('cities',$cities);
    }

    public function showWingmanSelect($city_id) {
        $wingmen = Group::where('name','=','Propel Wingman')->first()->wingman()->where('city_id','=',$city_id)->get();
        return View::make('city.wingman-select')->with('wingmen',$wingmen);
    }

    public function changeToWingman($wingman_id) {
        $_SESSION['user_id'] = $wingman_id;
        return Redirect::to('/');
    }

    public function backToNational() {
        $_SESSION['user_id'] = $_SESSION['original_id'];
        return Redirect::to('/');
    }
}
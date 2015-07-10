<?php

Class CityChangeController extends BaseController
{
    public function showCitySelect() {
        $cities = City::orderBy('name','asc')->get();
        return View::make('city.city-select')->with('cities',$cities);
    }

    public function showFellowSelect($city_id) {

        $fellowName = "Propel Multiplier";

        $fellows = Group::where('name','=',$fellowName)->first()->fellow()->where('city_id','=',$city_id)->get();
        return View::make('city.select-fellow')->with('fellows',$fellows);
    }

    public function changeToFellow($fellow_id) {
        $_SESSION['user_id'] = $fellow_id;
        return Redirect::to('/');
    }

    public function backToNational() {
        $_SESSION['user_id'] = $_SESSION['original_id'];
        return Redirect::to('/');
    }
}
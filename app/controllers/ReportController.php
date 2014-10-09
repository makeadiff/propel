<?php

class ReportController extends BaseController
{

    public function showCities()
    {
        $cities = City::all();

        return View::make('reports.class-status.select-city')->with('cities',$cities);
    }

    public function showReports()
    {
        return View::make('reports.select-report');
    }



}

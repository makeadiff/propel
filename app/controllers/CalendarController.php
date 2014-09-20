<?php

class CalendarController extends BaseController
{

    public function showStudents($wingman_id)
    {
        $students = Wingman::find($wingman_id)->student()->get();

        return View::make('calendar.student-list')->with('students',$students)->with('wingman_id',$wingman_id);
    }

    public function showCalendar($wingman_id,$student_id)
    {
        $cal = new CalendarLib("daily");

        $city = Wingman::find($wingman_id)->city()->first();
        $volunteers  = Volunteer::where('city_id','=',$city->id)->get();
        $subjects = Wingman::find($wingman_id)->city()->first()->subject()->get();
        $wingman_modules = WingmanModule::all();

        return View::make('calendar.calendar-view')->with('cal',$cal)->with('volunteers',$volunteers)->with('subjects',$subjects)
                        ->with('wingman_modules',$wingman_modules) ;
    }




}

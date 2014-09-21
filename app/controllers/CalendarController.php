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

        $GLOBALS['student_id'] = $student_id;

        return View::make('calendar.calendar-view')->with('cal',$cal)->with('volunteers',$volunteers)->with('subjects',$subjects)
                        ->with('wingman_modules',$wingman_modules)->with('student_id',$student_id)->with('wingman_id',$wingman_id) ;
    }

    public function createEdit()
    {
        $on_date = Input::get('on_date');
        $existing_ce = CalendarEvent::whereRaw("DATE(start_time) = '$on_date'")->where('student_id','=',Input::get('student_id'))->first();
        if(!empty($existing_ce)) {
            WingmanTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            VolunteerTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            CancelledCalendarEvent::where('calendar_event_id','=',$existing_ce->id)->delete();
            $existing_ce->delete();
        }


        $ce = new CalendarEvent;
        $ce->type = Input::get('type');
        $ce->start_time = new DateTime(Input::get('on_date') . ' ' . Input::get('start_time'));
        $ce->end_time = new DateTime(Input::get('on_date') . ' ' . Input::get('end_time'));
        $ce->student_id = Input::get('student_id');
        $ce->status = 'created';
        $ce->save();

        switch($ce->type) {
            case 'wingman_time' :
                $wt = new WingmanTime;
                $wt->wingman_id = Input::get('wingman_id');
                $wt->wingman_module_id = Input::get('wingman_module');
                $wt->calendar_event_id = $ce->id;
                $wt->save();
                break;

            case 'volunteer_time' :
                $vt = new VolunteerTime;
                $vt->volunteer_id = Input::get('volunteer');
                $vt->subject_id = Input::get('subject');
                $vt->calendar_event_id = $ce->id;
                $vt->save();
                break;
        }

        return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));

    }

    public function cancelEvent()
    {
        $on_date = Input::get('cancel_on_date');
        $existing_ce = CalendarEvent::whereRaw("DATE(start_time) = '$on_date'")->where('student_id','=',Input::get('student_id'))->first();
        $existing_ce->status = 'cancelled';
        $existing_ce->save();

        $cancelled_event = new CancelledCalendarEvent();
        $cancelled_event->calendar_event_id = $existing_ce->id;
        $cancelled_event->reason = Input::get('reason');
        $cancelled_event->comment = Input::get('comment');
        $cancelled_event->save();

        return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));

    }




}

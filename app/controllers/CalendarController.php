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

        $this->setGroup();

        $cal = new CalendarLib("daily");

        $city = Wingman::find($wingman_id)->city()->first();
        $volunteers  = Volunteer::where('city_id','=',$city->id)->get();
        $subjects = Wingman::find($wingman_id)->city()->first()->subject()->get();
        $wingman_modules = WingmanModule::all();

        /*$calendarEvents = DB::table('propel_calendarEvents as P')->select('P.id','P.type as title','P.start_time as start','P.end_time as end')->where('student_id','=',$student_id)->get();
        */
        $calendarEvents = DB::table('propel_calendarEvents as P')->leftJoin('propel_cancelledCalendarEvents as Q','P.id','=','Q.calendar_event_id')->leftJoin('propel_wingmanTimes as R','R.calendar_event_id','=','P.id')->leftJoin('propel_volunteerTimes as S','S.calendar_event_id','=','P.id')->leftJoin('User as T','T.id','=','S.volunteer_id')->leftJoin('User as U','U.id','=','R.wingman_id')->leftJoin('propel_wingmanModules as V','V.id','=','R.wingman_module_id')->leftJoin('propel_subjects as W','W.id','=','S.subject_id')->select('P.id','P.type as title','P.start_time as start','P.end_time as end','P.status','Q.reason as reason','Q.comment as comment','U.name as wingman_name','T.name as volunteer_name','S.volunteer_id as volunteer_id','R.wingman_id as wingman_id','V.id as module_id','W.id as subject_id','V.name as module_name','W.name as subject_name')->where('student_id','=',$student_id)->get();
        foreach ($calendarEvents as $calendarEvent) {
            /*if($calendarEvent->title == 'wingman_time'){
                $calendarEvent->title = 'Wigman Time';
            }
            elseif ($calendarEvent->title == 'child_busy') {
                $calendarEvent->title = 'Child Busy';
            }
            elseif ($calendarEvent->title == 'volunteer_time') {
                $calendarEvent->title = 'Volunteer Time';
            }*/
            $calendarEvent->title = str_replace('_', ' ',$calendarEvent->title);
            $calendarEvent->title = ucwords($calendarEvent->title);
            $calendarEvent->reason = str_replace('_', ' ',$calendarEvent->reason);
            $calendarEvent->reason = ucwords($calendarEvent->reason);

        }
        $calendarEvents = json_encode($calendarEvents);
        //return $calendarEvents;
        
        $GLOBALS['student_id'] = $student_id;
        return View::make('calendar.calendar-view')->with('cal',$cal)->with('volunteers',$volunteers)->with('subjects',$subjects)
                        ->with('wingman_modules',$wingman_modules)->with('student_id',$student_id)->with('wingman_id',$wingman_id)->with('calendarEvents',$calendarEvents) ;
    }

    public function createEdit()
    {

        if(Input::get('type') == "")
            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));

        if(Input::get('subject') == "" && Input::get('type')  == 'volunteer_time')
            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')))->with('error', 'Subject not selected.');

        /*$on_date = Input::get('on_date');
        $existing_ce = CalendarEvent::whereRaw("DATE(start_time) = '$on_date'")->where('student_id','=',Input::get('student_id'))->first();
        if(!empty($existing_ce)) {
            WingmanTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            VolunteerTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            CancelledCalendarEvent::where('calendar_event_id','=',$existing_ce->id)->delete();
            $existing_ce->delete();
        }*/


        $ce = new CalendarEvent;
        $ce->type = Input::get('type');
        $ce->start_time = new DateTime(Input::get('on_date') . ' ' . Input::get('start_time'));
        $ce->end_time = new DateTime(Input::get('end_date') . ' ' . Input::get('end_time'));
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

    public function editEvent(){

        //return Input::get('edit_type');
        if(Input::get('edit_type') == "")
            return Redirect::to(URL::to('/calendar/' . Input::get('edit_wingman_id') . '/' . Input::get('edit_student_id')));

        if(Input::get('edit_subject') == "" && Input::get('edit_type')  == 'volunteer_time')
            return Redirect::to(URL::to('/calendar/' . Input::get('edit_wingman_id') . '/' . Input::get('edit_student_id')))->with('error', 'Subject not selected.');

        $id = Input::get('calendar_id');
        //return $id;
        $existing_ce = CalendarEvent::where('id','=',$id)->first();
        if(!empty($existing_ce)) {
            WingmanTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            VolunteerTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            CancelledCalendarEvent::where('calendar_event_id','=',$existing_ce->id)->delete();
        }
        //return Input::get('edit_start_date');
        $existing_ce->type = Input::get('edit_type');
        $existing_ce->start_time = new DateTime(Input::get('edit_start_date') . ' ' . Input::get('edit_start_time'));
        $existing_ce->end_time = new DateTime(Input::get('end_end_date') . ' ' . Input::get('edit_end_time'));
        $existing_ce->student_id = Input::get('edit_student_id');
        $existing_ce->status = 'created';
        $existing_ce->save();

        switch($existing_ce->type) {
            case 'wingman_time' :
                $wt = new WingmanTime;
                $wt->wingman_id = Input::get('edit_wingman_id');
                $wt->wingman_module_id = Input::get('edit_wingman_module');
                $wt->calendar_event_id = $existing_ce->id;
                $wt->save();
                break;

            case 'volunteer_time' :
                $vt = new VolunteerTime;
                $vt->volunteer_id = Input::get('edit_volunteer');
                $vt->subject_id = Input::get('edit_subject');
                $vt->calendar_event_id = $existing_ce->id;
                $vt->save();
                break;
        }

        return Redirect::to(URL::to('/calendar/' . Input::get('edit_wingman_id') . '/' . Input::get('edit_student_id')));


    }

    public function cancelEvent()
    {
        //$on_date = Input::get('cancel_on_date');

        if(Input::get('reason') == 'mistaken_entry') {
            $existing_ce = CalendarEvent::where('id','=',Input::get('calendar_event_id'))->first();
            $existing_ce->delete();
            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));
        }else {
            $existing_ce = CalendarEvent::where('id','=',Input::get('calendar_event_id'))->first();
            $existing_ce->status = 'cancelled';
            $existing_ce->save();
            $existing_cancelled_ce = CancelledCalendarEvent::where('calendar_event_id','=',Input::get('calendar_event_id'))->first();
            if(!empty($existing_cancelled_ce)){
                $existing_cancelled_ce->delete();
            }
            $cancelled_event = new CancelledCalendarEvent();
            $cancelled_event->calendar_event_id = $existing_ce->id;
            $cancelled_event->reason = Input::get('reason');
            $cancelled_event->comment = Input::get('comment');
            $cancelled_event->save();

            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));
        }
    }

    public function selectWingman()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $wingmen = $fellow->wingman()->get();

        return View::make('calendar.select-wingman')->with('wingmen',$wingmen);
    }

    public static function setGroup()
    {
        $user_id = $_SESSION['user_id'];

        $user = Volunteer::find($user_id);

        $groups = $user->group()->get();

        $fellow = false;
        $wingman = false;

        foreach($groups as $group) {
            if($group->name == 'Propel Fellow')
                $fellow = true;
            elseif($group->name == 'Propel Wingman')
                $wingman = true;
        }

        if($fellow == true)
            View::share('user_group','Propel Fellow');
        elseif($wingman == true)
            View::share('user_group','Propel Wingman');
    }

    public function approveView(){
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);
        $wingmen = $fellow->wingman()->get();
        //$current_date = new DateTime()
        $current_month = date('c', strtotime("+1 month"));
        $i=0;
        $datalist = DB::table('User as A')->join('propel_fellow_wingman as B','A.id','=','B.fellow_id')->join('propel_student_wingman as C','C.wingman_id','=','B.wingman_id')->join('User as D','D.id','=','B.wingman_id')->join('Student as E','E.id','=','C.student_id')->join('propel_calendarEvents as F','F.student_id','=','C.student_id')->select('B.wingman_id as wingman_id','A.name as fellow_name','D.id as wingman_id','D.name as wingman_name','E.id as student_id','E.name as student_name','F.start_time as month')->where('A.id','=',$user_id)->where('F.status','!=','approved')->orderBy('student_id')->orderBy('month')->where('F.start_time','<=',$current_month)->get();
        /*foreach ($wingmen as $wingman) {
            $students[$i] = $wingman->student()->get();
            $i++;
        }*/

        //strftime('%Y-%m'
        //return $students;
        return View::make('calendar.approve-calendar')->with('datalist',$datalist);
    }

    public function approve($student_id,$month,$year){
        $calendar_event = CalendarEvent::where('student_id','=',$student_id)->get();
        $wingman_id = DB::table('propel_student_wingman')->select('wingman_id')->where('student_id','=',$student_id)->first();
        //return $calendar_event;
        foreach ($calendar_event as $event) {
            $monthCurr = date('n',strtotime($event->start_time));
            $yearCurr = date('Y',strtotime($event->start_time));
            $status = $event->status;
            if($monthCurr==$month && $yearCurr==$year && $status!= 'cancelled'){
                $event->status = 'approved';
                $event->save();
            }
        }
        $prev_url = $_SERVER['HTTP_REFERER'];
        if(strpos($prev_url,"approve-calendar") !== false){
            return Redirect::to(URL::to('/calendar/approve-calendar/'));
        }
        else{
            return Redirect::to(URL::to('/calendar/' .$wingman_id->wingman_id. '/' . $student_id ));
        }
    }

    public function approveSelected()
    {
        $wingman_id = $_SESSION['user_id'];
        $data = Input::get('submit_value');
        if(is_array($data)){
            foreach ($data as $datapoint) {
                $fragments = explode('/', $datapoint);
                $student_id = $fragments[0];
                $month = $fragments[1];
                $year = $fragments[2];
                $calendar_event = CalendarEvent::where('student_id','=',$student_id)->get();
                $wingman_id = DB::table('propel_student_wingman')->select('wingman_id')->where('student_id','=',$student_id)->first();
                foreach ($calendar_event as $event) {
                    $monthCurr = date('n',strtotime($event->start_time));
                    $yearCurr = date('Y',strtotime($event->start_time));
                    $status = $event->status;
                    if($monthCurr==$month && $yearCurr==$year && $status!= 'cancelled'){
                        $event->status = 'approved';
                        $event->save();
                    }
                }
            }
        }
        return Redirect::to(URL::to('/calendar/approve-calendar/'));
    }
}

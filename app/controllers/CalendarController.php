        <?php

class CalendarController extends BaseController
{
    private $asvGroupName = "Propel ASV";

    public function showStudents($wingman_id)
    {
        $students = Wingman::find($wingman_id)->student()->get();

        return View::make('calendar.student-list')->with('students',$students)->with('wingman_id',$wingman_id);
    }

    public function showCenterCalendar($center_id)
    {

        $this->setGroup();

        $calendarEvents = DB::table('propel_calendarEvents as P')->leftJoin('propel_cancelledCalendarEvents as Q','P.id','=','Q.calendar_event_id')
            ->leftJoin('propel_wingmanTimes as R','R.calendar_event_id','=','P.id')->leftJoin('propel_volunteerTimes as S','S.calendar_event_id','=','P.id')
            ->leftJoin('User as T','T.id','=','S.volunteer_id')->leftJoin('User as U','U.id','=','R.wingman_id')
            ->leftJoin('propel_wingmanModules as V','V.id','=','R.wingman_module_id')->leftJoin('propel_subjects as W','W.id','=','S.subject_id')
            ->join('Student','Student.id','=','P.student_id')
            ->select('P.id','P.type as title','P.start_time as start','P.end_time as end','P.status','Q.reason as reason','Q.comment as comment',
                'U.name as wingman_name','T.name as volunteer_name','S.volunteer_id as volunteer_id','R.wingman_id as wingman_id','V.id as module_id',
                'W.id as subject_id','V.name as module_name','W.name as subject_name','Student.name as student_name')
            ->where('Student.center_id','=',$center_id)->get();


        foreach ($calendarEvents as $calendarEvent) {

            $calendarEvent->title = $calendarEvent->student_name . " : " . str_replace('_', ' ',$calendarEvent->title) ;
            $calendarEvent->title = ucwords($calendarEvent->title);
            $calendarEvent->reason = str_replace('_', ' ',$calendarEvent->reason);
            $calendarEvent->reason = ucwords($calendarEvent->reason);

        }
        $calendarEvents = json_encode($calendarEvents);

        return View::make('calendar.center-calendar-view')->with('calendarEvents',$calendarEvents) ;
    }

    public function showCalendar($wingman_id,$student_id)
    {

        $this->setGroup();
        $city = Wingman::find($wingman_id)->city()->first();
        $volunteers = Group::where('name',$this->asvGroupName)->first()->volunteer()->where('city_id','=',$city->id)->where('status','=',1)->where('user_type','=','volunteer')->groupby('id')->get();

        //return $volunteers;
        $subjects = Wingman::find($wingman_id)->city()->first()->subject()->get();
        $wingman_modules = WingmanModule::all();
        /*$calendarEvents = DB::table('propel_calendarEvents as P')->select('P.id','P.type as title','P.start_time as start','P.end_time as end')->where('student_id','=',$student_id)->get();
        */
        $calendarEvents = DB::table('propel_calendarEvents as P')->leftJoin('propel_cancelledCalendarEvents as Q','P.id','=','Q.calendar_event_id')->leftJoin('propel_wingmanTimes as R','R.calendar_event_id','=','P.id')->leftJoin('propel_volunteerTimes as S','S.calendar_event_id','=','P.id')->leftJoin('User as T','T.id','=','S.volunteer_id')->leftJoin('User as U','U.id','=','R.wingman_id')->leftJoin('propel_wingmanModules as V','V.id','=','R.wingman_module_id')->leftJoin('propel_subjects as W','W.id','=','S.subject_id')->select('P.id','P.type as title','P.start_time as start','P.end_time as end','P.status','Q.reason as reason','Q.comment as comment','U.name as wingman_name','T.name as volunteer_name','S.volunteer_id as volunteer_id','R.wingman_id as wingman_id','V.id as module_id','W.id as subject_id','V.name as module_name','W.name as subject_name')->where('student_id','=',$student_id)->get();

        foreach ($calendarEvents as $calendarEvent) {

            $calendarEvent->title = str_replace('_', ' ',$calendarEvent->title);
            $calendarEvent->title = ucwords($calendarEvent->title);
            $calendarEvent->reason = str_replace('_', ' ',$calendarEvent->reason);
            $calendarEvent->reason = ucwords($calendarEvent->reason);

        }

        $calendarEvents = json_encode($calendarEvents);
        $student_name = Student::where('id','=',$student_id)->first();
        $GLOBALS['student_id'] = $student_id;

        return View::make('calendar.calendar-view')->with('volunteers',$volunteers)->with('subjects',$subjects)
                        ->with('wingman_modules',$wingman_modules)->with('student_id',$student_id)->with('wingman_id',$wingman_id)->with('calendarEvents',$calendarEvents)->with('student_name',$student_name->name) ;

    }

    public function showAsvCalendar($asv_id)
    {

        $this->setGroup();

        $city = Volunteer::find($asv_id)->city()->first();
        $students  = $city->student()->get();
        $subjects = Volunteer::find($asv_id)->city()->first()->subject()->get();


        $calendarEvents = DB::table('propel_calendarEvents as ce')->leftJoin('propel_cancelledCalendarEvents as cce','ce.id','=','cce.calendar_event_id')
            ->join('propel_volunteerTimes as vt','vt.calendar_event_id','=','ce.id')
            ->leftJoin('User as u','u.id','=','vt.volunteer_id')
            ->leftJoin('propel_subjects as sub','sub.id','=','vt.subject_id')
            ->join('Student','Student.id','=','ce.student_id')
            ->select('ce.id','ce.type as title','ce.start_time as start','ce.end_time as end','ce.status','cce.reason as reason','cce.comment as comment'
                ,'u.name as volunteer_name','vt.volunteer_id as volunteer_id',
                'sub.id as subject_id','sub.name as subject_name','Student.name as student_name')
            ->where('vt.volunteer_id','=',$asv_id)->get();


        foreach ($calendarEvents as $calendarEvent) {

            $calendarEvent->title = $calendarEvent->student_name . " : " . str_replace('_', ' ',$calendarEvent->title) ;
            $calendarEvent->title = ucwords($calendarEvent->title);
            $calendarEvent->reason = str_replace('_', ' ',$calendarEvent->reason);
            $calendarEvent->reason = ucwords($calendarEvent->reason);

        }
        $calendarEvents = json_encode($calendarEvents);

        return View::make('calendar.asv-calendar-view')->with('calendarEvents',$calendarEvents)->with('city',$city)->with('students',$students)->with('subjects',$subjects) ->with('volunteer_id',$asv_id);
    }

    public function createEvent()
    {

        if(Input::get('type') == "")
            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));

        if(Input::get('subject') == "" && Input::get('type')  == 'volunteer_time')
            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')))->with('error', 'Subject not selected.');

        //Foreach because in case there are multiple student ids (in case of asv calendar)

        $students = (array)Input::get('student_id');
        $length = count($students);

        foreach($students as $index => $student) {
            $ce = new CalendarEvent;
            $ce->type = Input::get('type');
            $ce->start_time = new DateTime(Input::get('on_date') . ' ' . Input::get('start_time'));
            $ce->end_time = new DateTime(Input::get('end_date') . ' ' . Input::get('end_time'));
            $ce->student_id = $student;
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
                    $vt->volunteer_id = Input::get('volunteer_id');
                    $vt->subject_id = Input::get('subject');
                    $vt->calendar_event_id = $ce->id;
                    $vt->save();

                    //To ensure that in case of request from ASV calendar, where there would be multiple students chosen, the SMS is only sent once and not multiple times.
                    //Here it is sent on the last iteration.

                    if($index == $length -1){
                        //Send SMS to the volunteer informing them about the class

                        $volunteer = Volunteer::find(Input::get('volunteer_id'));

                        //To get the first name
                        list($volunteer_name) = explode(" ",$volunteer->name);
                        $user = Volunteer::find($_SESSION['user_id']);
                        list($user_name) = explode(" ", $user->name);

                        //To get correctly formatted date and time
                        $on_date = date("d-M", strtotime(Input::get('on_date')));
                        $on_time = Input::get('start_time');

                        $student = Student::find($ce->student_id);
                        $center_name = $student->center()->first()->name;

                        $sms = new SMSController();
                        $sms->message = "Hi $volunteer_name,\n\nYou have a class scheduled at $center_name on $on_date($on_time).\n\nPlease contact $user_name($user->phone) for more details.";
                        $sms->number = $volunteer->phone;
                        $sms->send();
                    }



                    break;
            }
        }

        //To check whether the request is coming from the child calendar or the asv calendar
        if(Request::segment(2) == 'asv') {
            return Redirect::to(URL::to('/calendar/asv/'  . Input::get('volunteer_id')));
        }else {
            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));
        }



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
        //return Input::get('edit_start_date').' '.Input::get('edit_start_time');
        $existing_ce->start_time = new DateTime(Input::get('edit_start_date') . ' ' . Input::get('edit_start_time'));
        $existing_ce->end_time = new DateTime(Input::get('edit_end_date') . ' ' . Input::get('edit_end_time'));
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

                //Send SMS to the volunteer informing them about the class

                $volunteer = Volunteer::find(Input::get('edit_volunteer'));

                //To get the first name
                list($volunteer_name) = explode(" ",$volunteer->name);
                $user = Volunteer::find($_SESSION['user_id']);
                list($user_name) = explode(" ", $user->name);

                //To get correctly formatted date and time
                $on_date = date("d-M", strtotime(Input::get('on_date')));
                $on_time = Input::get('start_time');

                $student = Student::find($existing_ce->student_id);
                $center_name = $student->center()->first()->name;

                $sms = new SMSController();
                $sms->message = "Hi $volunteer_name,\n\nYou have a class scheduled at $center_name on $on_date($on_time).\n\nPlease contact $user_name($user->phone) for more details.";
                $sms->number = $volunteer->phone;
                $sms->send();


                break;
        }

        return Redirect::to(URL::to('/calendar/' . Input::get('edit_wingman_id') . '/' . Input::get('edit_student_id')));


    }

    public function rescheduleEvent(){
        if(Input::get('reschedule_event_type') == "")
            return Redirect::to(URL::to('/calendar/' . Input::get('reschedule_wingman_id') . '/' . Input::get('reschedule_student_id')));

        if(Input::get('reschedule_subject') == "" && Input::get('reschedule_type')  == 'volunteer_time')
            return Redirect::to(URL::to('/calendar/' . Input::get('reschedule_wingman_id') . '/' . Input::get('reschedule_student_id')))->with('error', 'Subject not selected.');

        $id = Input::get('rescheduleCalendar_id');

        $existing_ce = CalendarEvent::where('id','=',$id)->first();
        if(!empty($existing_ce)) {
            WingmanTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            VolunteerTime::where('calendar_event_id','=',$existing_ce->id)->delete();
            CancelledCalendarEvent::where('calendar_event_id','=',$existing_ce->id)->delete();
        }

        $existing_ce->type = Input::get('reschedule_event_type');
        //return Input::get('edit_start_date').' '.Input::get('edit_start_time');
        $existing_ce->start_time = new DateTime(Input::get('reschedule_start_date') . ' ' . Input::get('reschedule_start_time'));
        $existing_ce->end_time = new DateTime(Input::get('reschedule_end_date') . ' ' . Input::get('reschedule_end_time'));
        $existing_ce->student_id = Input::get('reschedule_student_id');
        $existing_ce->status = 'approved';
        $existing_ce->save();

        switch($existing_ce->type) {
            case 'wingman_time' :
                $wt = new WingmanTime;
                $wt->wingman_id = Input::get('reschedule_wingman_id');
                $wt->wingman_module_id = Input::get('reschedule_wingman_module');
                $wt->calendar_event_id = $existing_ce->id;
                $wt->save();
                break;

            case 'volunteer_time' :
                $vt = new VolunteerTime;
                $vt->volunteer_id = Input::get('reschedule_volunteer');
                $vt->subject_id = Input::get('reschedule_subject');
                $vt->calendar_event_id = $existing_ce->id;
                $vt->save();

                //Send SMS to the volunteer informing them about the class

                $volunteer = Volunteer::find(Input::get('reschedule_volunteer'));

                //To get the first name
                list($volunteer_name) = explode(" ",$volunteer->name);
                $user = Volunteer::find($_SESSION['user_id']);
                list($user_name) = explode(" ", $user->name);

                //To get correctly formatted date and time
                $on_date = date("d-M", strtotime(Input::get('on_date')));
                $on_time = Input::get('start_time');

                $student = Student::find($existing_ce->student_id);
                $center_name = $student->center()->first()->name;

                $sms = new SMSController();
                $sms->message = "Hi $volunteer_name,\n\nYou have a class scheduled at $center_name on $on_date($on_time).\n\nPlease contact $user_name($user->phone) for more details.";
                $sms->number = $volunteer->phone;
                $sms->send();

                break;
        }

        return Redirect::to(URL::to('/calendar/' . Input::get('reschedule_wingman_id') . '/' . Input::get('reschedule_student_id')));


    }

    public function cancelEvent()
    {
        $existing_ce = CalendarEvent::where('id','=',Input::get('calendar_event_id'))->first();

        if ($existing_ce->type == "volunteer_time") {

            //Info required to send SMS

            $volunteer = $existing_ce->volunteerTime()->first()->volunteer()->first();

            //To get the first name
            list($volunteer_name) = explode(" ",$volunteer->name);
            $user = Volunteer::find($_SESSION['user_id']);
            list($user_name) = explode(" ", $user->name);

            //To get correctly formatted date and time
            $on_date = date("d-M", strtotime($existing_ce->start_time));
            $on_time = Input::get('start_time');

            $student = Student::find($existing_ce->student_id);
            $center_name = $student->center()->first()->name;
            //Send SMS to the volunteer informing them about the class

            $sms = new SMSController();

            switch(Input::get('reason')) {
                case 'mistaken_entry' :
                    $sms->message = "Hi $volunteer_name,\n\nYour class at $center_name on $on_date has been cancelled since it was a mistaken entry.\n\nPlease contact $user_name($user->phone) for more details.";
                    break;
                case 'volunteer_not_available' :
                    $sms->message = "Hi $volunteer_name,\n\nYour class at $center_name on $on_date has been cancelled since you are not available.\n\nPlease contact $user_name($user->phone) for more details.";
                    break;
                case 'student_not_available' :
                    $sms->message = "Hi $volunteer_name,\n\nYour class at $center_name on $on_date has been cancelled since the student is not available.\n\nPlease contact $user_name($user->phone) for more details.";
                    break;

            }
            $sms->number = $volunteer->phone;
            $sms->send();

        }

        if(Input::get('reason') == 'mistaken_entry') {

            $existing_ce->delete();

        }else {
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

        }

        //To check whether the request is coming from the child calendar or the asv calendar
        if(Request::segment(2) == 'asv') {
            return Redirect::to(URL::to('/calendar/asv/'  . Input::get('volunteer_id')));
        }else {
            return Redirect::to(URL::to('/calendar/' . Input::get('wingman_id') . '/' . Input::get('student_id')));
        }
    }

    public function selectWingman()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $wingmen = $fellow->wingman()->get();
        //return $wingmen;
        return View::make('calendar.select-wingman')->with('wingmen',$wingmen);
    }

    public function selectCenter()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $centers = $fellow->city()->first()->center()->where('status','1')->get();

        return View::make('calendar.select-center')->with('centers',$centers);
    }



    public function selectAsv()
    {

        //echo 'SELECT ASV SECTION';

        $user_id = $_SESSION['user_id'];
        //echo 'User Id: '.$user_id;
        $fellow = Fellow::find($user_id);
        $city = $fellow->city()->first();

        $asvs = Group::where('name',$this->asvGroupName)->first()->volunteer()->where('city_id','=',$city->id)->where('user_type','=','volunteer')->where('status','=',1)->orderBy('name','ASC')->groupby('id')->get();
        //return $asvs;

        return View::make('calendar.select-asv')->with('asvs',$asvs);
    }


    public static function setGroup()
    {
        $user_id = $_SESSION['user_id'];

        $user = Volunteer::find($user_id);

        $groups = $user->group()->get();

        $fellow = false;
        $wingman = false;

        foreach($groups as $group) {
            if($group->name == 'Propel Multiplier')
                $fellow = true;
            elseif($group->name == 'Propel Wingman')
                $wingman = true;
        }

        if($fellow == true)
            View::share('user_group','Propel Multiplier');
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
        $datalist = DB::table('User as A')->join('propel_fellow_wingman as B','A.id','=','B.fellow_id')->join('propel_student_wingman as C','C.wingman_id','=','B.wingman_id')->join('User as D','D.id','=','B.wingman_id')->join('Student as E','E.id','=','C.student_id')->join('propel_calendarEvents as F','F.student_id','=','C.student_id')->select('B.wingman_id as wingman_id','A.name as fellow_name','D.id as wingman_id','D.name as wingman_name','E.id as student_id','E.name as student_name','F.start_time as month')->where('A.id','=',$user_id)->where('F.status','!=','approved')->where('F.status','!=','cancelled')->orderBy('student_id')->orderBy('month')->where('F.start_time','<=',$current_month)->get();
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

    public function approvalSummary(){
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);
        $wingmen = $fellow->wingman()->get();
        //$current_date = new DateTime()
        $current_month = date('c', strtotime("+1 month"));
        $i=0;
        $datalist = DB::table('User as A')->join('propel_fellow_wingman as B','A.id','=','B.fellow_id')->join('propel_student_wingman as C','C.wingman_id','=','B.wingman_id')->join('User as D','D.id','=','B.wingman_id')->join('Student as E','E.id','=','C.student_id')->join('propel_calendarEvents as F','F.student_id','=','C.student_id')->select('B.wingman_id as wingman_id','A.name as fellow_name','D.id as wingman_id','D.name as wingman_name','E.id as student_id','E.name as student_name','F.start_time as month')->where('A.id','=',$user_id)->where('F.status','!=','approved')->where('F.status','!=','cancelled')->orderBy('student_id')->orderBy('month')->where('F.start_time','<=',$current_month)->get();

        return $datalist;
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

    public function calendarApproval($city_id = null,$start_date = null,$end_date = null){

        $cities = DB::table('City')->where('id','<',26)->orderBy('name','ASC')->get();

        if($city_id == 'null' || !isset($city_id)){

            $query = DB::table('propel_calendarEvents as A')->join('Student as B','B.id','=','A.student_id')->join('Center as C','C.id','=','B.center_id')->join('City as D','D.id','=','C.city_id')->join('propel_student_wingman as E','E.student_id','=','A.student_id')->join('User as F','F.id','=','E.wingman_id');

            $month = Date('m');

            $fetchQuery = $query->select('A.id','D.name','D.id as cityId','A.status',DB::raw('MONTH(A.start_time) as month'),DB::raw('count(D.id)'),DB::raw('count(A.start_time) as event_count'),'A.student_id')->groupby('A.student_id')->groupby(DB::raw('MONTH(A.start_time)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('D.id','<',26)->where('F.user_type','=','volunteer')->where('F.status','=',1);

            if($start_date!= 'null' && isset($start_date)){
                $start = date('Y-m-d 00:00:00',strtotime($start_date));
                $fetchQuery = $fetchQuery->where('A.start_time','>=',$start);
            }
            else{
                $fetchQuery = $fetchQuery->where('A.start_time','>=',$this->year_time);
            }

            if($end_date!= 'null' && isset($end_date)){
                $end = date('Y-m-d 00:00:00',strtotime($end_date));
                $fetchQuery = $fetchQuery->where('A.end_time','<=',$end);
            }

            $data = $fetchQuery->orderBy('D.name','ASC')->orderBy('A.student_id','ASC')->orderBy('month','ASC')->get();

            //Grouping Calendar's status with the student_ids and the month of the event to check the number of approved calendars
            //return $data;

            $datas = array();

            $city_id = 0;

            foreach ($data as $calendar_data) {

                if($calendar_data->cityId!=$city_id){
                    $city_id = $calendar_data->cityId;

                    $month = 0; //Reset the month to 0 for a different city.
                    $student_id = 0; //Reset the student_id to 0 for a different city.
                    $flag = true; //Reset the flag for a different city.

                    $datas[$city_id]['approved'] = 0;
                    $datas[$city_id]['created'] = 0;

                    $datas[$city_id]['city_id'] = $calendar_data->cityId;
                    $datas[$city_id]['city_name'] = $calendar_data->name;

                    $student_id = $calendar_data->student_id;
                    $month = $calendar_data->month;

                    if($calendar_data->status == "approved" || $calendar_data->status == "attended"){
                        $datas[$city_id]['approved']++;
                        $flag = false;
                    }
                    elseif($calendar_data->status == "created"){
                        $datas[$city_id]['created']++;
                    }
                }
                else{
                    if($calendar_data->student_id==$student_id && $calendar_data->month==$month){
                        if(($calendar_data->status == "approved" || $calendar_data->status == "attended") && $flag){
                            if($datas[$city_id]['created']=0)
                                $datas[$city_id]['approved']++;
                        }
                        elseif($calendar_data->status == "created"){
                            $datas[$city_id]['created']++;
                            if($datas[$city_id]['approved']!=0)
                                $datas[$city_id]['approved']--;
                        }
                    }
                    elseif($calendar_data->student_id==$student_id && $calendar_data->month!=$month){
                        $flag=false; //Reset the flag for a different Month.
                        $month = $calendar_data->month;
                        if($calendar_data->status == "approved" || $calendar_data->status == "attended"){
                            $datas[$city_id]['approved']++;
                        }
                        elseif($calendar_data->status == "created"){
                            $datas[$city_id]['created']++;
                        }
                    }
                    elseif($calendar_data->student_id!=$student_id){
                        $flag=false; //Reset the flag for a different Student.
                        $student_id = $calendar_data->student_id;
                        $month = $calendar_data->month;
                        if($calendar_data->status == "approved" || $calendar_data->status == "attended"){
                            $datas[$city_id]['approved']++;
                        }
                        elseif($calendar_data->status == "created"){
                            $datas[$city_id]['created']++;
                        }
                    }
                    $city_id = $calendar_data->cityId;
                }
            }

            return View::make('reports.calendar-approval')->with('datas',$datas)->with('start_date',$start_date)->with('end_date',$end_date)->with('cities',$cities);

        }
        else {

            $query = DB::table('propel_calendarEvents as A')->join('Student as B','B.id','=','A.student_id')->join('Center as C','C.id','=','B.center_id')->join('City as D','D.id','=','C.city_id')->join('propel_student_wingman as E','E.student_id','=','B.id')->join('User as F','F.id','=','E.wingman_id')->join('UserGroup as G','G.user_id','=','E.wingman_id')->join('Group as H','H.id','=','G.group_id');


            $fetchQuery = $query->select('A.id','D.name as city_name','D.id as city_id','F.id as wingman_id','B.id as student_id','F.name as wingman_name','B.name as student_name','A.status',DB::raw('count(A.status) as event_count'),DB::raw('count(D.id)'),DB::raw('MONTH(A.start_time) as month'),'A.start_time')->groupby(DB::raw('MONTH(A.start_time)'))->groupby('B.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('D.id','=',$city_id)->where('F.status','=','1')->where('F.user_type','=','volunteer')->whereIn('H.id',['348','365'])->orderBy('B.id','ASC');


            if($start_date!= 'null' && isset($start_date)){
                $start = date('Y-m-d 00:00:00',strtotime($start_date));
                $fetchQuery = $fetchQuery->where('A.start_time','>=',$start);
            }
            else{
                $fetchQuery = $fetchQuery->where('A.start_time','>=',$this->year_time);
            }

            if($end_date!= 'null' && isset($end_date)){
                $end = date('Y-m-d 00:00:00',strtotime($end_date));
                $fetchQuery = $fetchQuery->where('A.end_time','<=',$end);
            }

            $data = $fetchQuery->orderBy('D.name','ASC')->orderBy('A.student_id','ASC')->orderBy('month','ASC')->get();

        //return $data;

            $datas = array();

            $student_id = 0;

            foreach ($data as $calendar_data) {

                if($calendar_data->student_id!=$student_id){
                    $student_id = $calendar_data->student_id;

                    $month = 0; //Reset the month to 0 for a different city.
                    $flag = true; //Reset the flag for a different city.

                    $datas[$student_id]['approved'] = 0;
                    $datas[$student_id]['created'] = 0;

                    $datas[$student_id]['wingman_id'] = $calendar_data->wingman_id;
                    $datas[$student_id]['wingman_name'] = $calendar_data->wingman_name;
                    $datas[$student_id]['student_id'] = $calendar_data->student_id;
                    $datas[$student_id]['student_name'] = $calendar_data->student_name;

                    //$student_id = $calendar_data->student_id;
                    $month = $calendar_data->month;

                    if($calendar_data->status == "approved" || $calendar_data->status == "attended"){
                        $datas[$student_id]['approved']++;
                        $flag = false;
                    }
                    elseif($calendar_data->status == "created"){
                        $datas[$student_id]['created']++;
                    }
                }
                else{
                    if($calendar_data->month==$month){
                        if(($calendar_data->status == "approved" || $calendar_data->status == "attended") && $flag){
                            if($datas[$student_id]['created']=0)
                                $datas[$student_id]['approved']++;
                        }
                        elseif($calendar_data->status == "created"){
                            $datas[$student_id]['created']++;
                            if($datas[$student_id]['approved']!=0)
                                $datas[$student_id]['approved']--;
                        }
                    }
                    else{
                        $flag=false; //Reset the flag for a different Month.
                        $month = $calendar_data->month;
                        if($calendar_data->status == "approved" || $calendar_data->status == "attended"){
                            $datas[$student_id]['approved']++;
                        }
                        elseif($calendar_data->status == "created"){
                            $datas[$student_id]['created']++;
                        }
                    }
                    $student_id = $calendar_data->student_id;
                }
            }

            $cities = DB::table('City')->where('id','<',26)->orderBy('name','ASC')->get();

            $home = new HomeController;
            $home->setGroup();

            //return $datas;

            return View::make('reports.city-calendar-approval')->with('datas',$datas)->with('cities',$cities)->with('city_id',$city_id)->with('start_date',$start_date)->with('end_date',$end_date);
        }
    }

    /*
    public function calendarApproval(){

        $input = Input::all();
        $start = false;
        $end = false;

        $query = DB::table('propel_calendarEvents as A')->join('Student as B','B.id','=','A.student_id')->join('Center as C','C.id','=','B.center_id')->join('City as D','D.id','=','C.city_id');


        $month = Date('m');

        /*$fetchQuery = $query->select('A.id','D.name','D.id as cityId','A.status',DB::raw('count(A.status) as event_count'),DB::raw('count(D.id)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('D.id','<',26);

        return $fetchQuery->get();   */

        //$fetchQuery = $query->select('A.id',DB::raw('count(A.student_id)'),'D.name','D.id as cityId','A.status',DB::raw('MONTH(A.start_time) as month'),DB::raw('count(D.id)'),DB::raw('count(A.start_time) as event_count'))->groupby('A.student_id')->groupby(DB::raw('MONTH(A.start_time)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('D.id','<',26);

        //return $fetchQuery->orderBy('D.name','ASC')->orderBy('A.student_id','ASC')->get();

        /*if(count($input)>0){

            if(Input::get('start_date')!=""){
                $start = true;
            }
            if(Input::get('end_date')!=""){
                $end = true;
            }

            $start_date = date('c',strtotime(Input::get('start_date')));
            $end_date = date('c',strtotime(Input::get('end_date')));
            if($start){
                $data = $fetchQuery->where('A.start_time','>=',$start_date)->orderBy('D.name','ASC')->get();
            }
            else if($end){
                $data = $fetchQuery->where('A.end_time','<=',$end_date)->orderBy('D.name','ASC')->get();
            }
            else if($start && $end){
                $data = $fetchQuery->where('A.start_time','>=',$start_date)->where('A.end_time','<=',$end_date)->orderBy('D.name','ASC')->get();
            }
            else{
                $data = $fetchQuery->orderBy('D.name','ASC')->get();
            }
        }
        else{
            $data = $fetchQuery->orderBy('D.name','ASC')->get();
        }


        $datas = array();

        $city_id = 0;

        foreach ($data as $calendar_data) {

            if($calendar_data->cityId!=$city_id){
                $city_id = $calendar_data->cityId;

                $datas[$city_id]['city_id'] = $calendar_data->cityId;
                $datas[$city_id]['city_name'] = $calendar_data->name;
                if($calendar_data->status == 'approved'){
                    $datas[$city_id]['approved'] = $calendar_data->event_count;
                }
                if($calendar_data->status == 'created'){
                    $datas[$city_id]['created'] = $calendar_data->event_count;
                }
                if($calendar_data->status == 'attended'){
                    $datas[$city_id]['attended'] = $calendar_data->event_count;
                }

            }
            else{
                if($calendar_data->status == 'approved'){
                    $datas[$city_id]['approved'] = $calendar_data->event_count;
                }
                if($calendar_data->status == 'created'){
                    $datas[$city_id]['created'] = $calendar_data->event_count;
                }
                if($calendar_data->status == 'attended'){
                    $datas[$city_id]['attended'] = $calendar_data->event_count;
                }

                $city_id = $calendar_data->id;
            }
        }

        $cities = DB::table('City')->where('id','<',26)->orderBy('name','ASC')->get();

        if(count($input)>0){
            return View::make('reports.calendar-approval')->with('datas',$datas)->with('start_date',Input::get('start_date'))->with('end_date',Input::get('end_date'))->with('cities',$cities);
        }
        else{
            return View::make('reports.calendar-approval')->with('datas',$datas)->with('cities',$cities);
        }

    }*/

    public function calendarFilter(){

        $start = "/null";
        $end = "/null";
        $city_id = "/null";

        if(Input::get('city')!=""){
            $city_id = '/'.Input::get('city');
        }
        if(Input::get('start_date')!=""){
            $start = '/'.Input::get('start_date');
        }
        if(Input::get('end_date')!=""){
            $end = '/'.Input::get('end_date');
        }

        return Redirect::away(URL::to('/reports/calendar-approval').$city_id.$start.$end);
    }

}

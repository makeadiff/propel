<?php

class AttendanceController extends BaseController
{
    private $asvGroupName = "Propel ASV";

    public function showAttendanceToWingman($user_id)
    {
        $wingmans_kids = Wingman::find($user_id)->student()->get();

        if(empty($wingmans_kids[0]))
            return Redirect::to('error')->with('message','There are no students assigned to the wingman');

        $student_ids = array();
        foreach($wingmans_kids as $wk)
            $student_ids[] = $wk->id;

        $attended = CalendarEvent::whereIn('student_id', $student_ids)->where('type','<>','child_busy')->where('type','<>','wingman_time')->where(function($query){
                $query->where('status','approved')->orWhere('status','attended');})->get();
        //return $attended;
        return View::make('attendance.attended-list')->with('attended',$attended);
    }

    public function showAttendanceToFellow($wingman_id,$timeline = null)
    {

        $wingmans_kids = Wingman::find($wingman_id)->student()->get();

        if(empty($wingmans_kids[0]))
            return Redirect::to('error')->with('message','There are no students assigned to the wingman');

        $student_ids = array();
        foreach($wingmans_kids as $wk)
            $student_ids[] = $wk->id;

        $previousMonth = date("Y-m-d h:i:s", strtotime("-1 months"));
        $previousDate = date("M Y ", strtotime("-1 months"));

        if($timeline == null){
          $attended = CalendarEvent::whereIn('student_id', $student_ids)->where('type','<>','child_busy')->where(function($query){
                  $query->where('status','approved')->orWhere('status','attended');})->where('start_time','>=',$previousMonth)->orderBy('start_time','DESC')->get();
        }
        elseif($timeline == 'previous'){
          $attended = CalendarEvent::whereIn('student_id', $student_ids)->where('type','<>','child_busy')->where(function($query){
                  $query->where('status','approved')->orWhere('status','attended');})->where('start_time','>=',$this->year_time)->orderBy('start_time','DESC')->get();
        }

        return View::make('attendance.attended-list')->with('attended',$attended)->with('timeline',$timeline)->with('wingman_id',$wingman_id)->with('date',$previousDate);
    }

    public function save($user_id)
    {
        $attendance_data = Input::get('attended');
        $calender_data = Input::get('calender_entry');
        if(Input::get('volunteer_id') != null){
          $volunteer_id = Input::get('volunteer_id');
        }
        if(Input::get('start_time') != null){
          $start_time = Input::get('start_time');
        }

        $segment = Request::segment(2);

        foreach ($calender_data as $id => $value) {
          if($segment == 'asv'){
            $volunteer = $volunteer_id[$id];
            $start = $start_time[$id];
            $events = DB::table('propel_calendarEvents as A')
                          ->join('propel_volunteerTimes as B','B.calendar_event_id','=','A.id')
                          ->select('A.id as id')
                          ->where('A.start_time','=',$start)
                          ->where('B.volunteer_id','=',$volunteer)
                          ->get();
            foreach ($events as $event) {
              $calender_event = CalendarEvent::find($event->id);
              $calender_event->status = isset($attendance_data[$id]) ? 'attended' : 'approved';
              $calender_event->save();
            }
          }
          else{
            $calender_event = CalendarEvent::find($id);
            $calender_event->status = isset($attendance_data[$id]) ? 'attended' : 'approved';
            $calender_event->save();
          }
        }

        if($segment == 'wingman') {
            return Redirect::to(URL::to('/') . "/attendance/wingman/" . $user_id)->with('success', 'Attendence Saved.');
        }
        if($segment == 'asv') {
            return Redirect::to(URL::to('/') . "/attendance/asv")->with('success', 'Attendence Saved.');
        }
        else {
            return Redirect::to(URL::to('/') . "/attendance/" . $user_id)->with('success', 'Attendence Saved.');
        }

        return Redirect::to(URL::to('/') . "/attendance/" . $user_id)->with('success', 'Attendence Saved.');
    }

    public function selectProfile()
    {
        $user_id = $_SESSION['user_id'];
        return View::make('attendance.select-profile');
    }

    public function selectWingman()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $wingmen = $fellow->wingman()->get();

        return View::make('attendance.select-wingman')->with('wingmen',$wingmen);
    }

    public function selectAsv($timeline = null)
    {

        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);
        $city = $fellow->city()->first();

        $asvs = Group::where('name',$this->asvGroupName)->first()->volunteer()->where('city_id','=',$city->id)->where('user_type','=','volunteer')->where('status','=',1)->orderBy('name','ASC')->groupby('id')->get();

        // return $asvs;

        $asv_ids = array();
        foreach($asvs as $asv)
            $asv_ids[] = $asv->id;

        $previousMonth = date("Y-m-d h:i:s", strtotime("-1 months"));
        $previousDate = date("M Y ", strtotime("-1 months"));

        if($timeline == null){
          $attended = DB::table('propel_calendarEvents as A')
                      ->join('propel_volunteerTimes as B','A.id','=','B.calendar_event_id')
                      ->select('A.id','A.type as type','B.volunteer_id as volunteer_id','A.start_time as start_time')
                      ->whereIn('B.volunteer_id', $asv_ids)
                      ->where(function($query){ $query->where('status','approved')->orWhere('status','attended');})
                      ->where('start_time','>=',$previousMonth)
                      ->orderBy('start_time','DESC')
                      ->orderBy('B.volunteer_id','ASC')->get();

        }
        elseif($timeline == 'previous'){
          $attended = DB::table('propel_calendarEvents as A')
                      ->join('propel_volunteerTimes as B','A.id','=','B.calendar_event_id')
                      ->select('A.id','A.type as type','B.volunteer_id as volunteer_id','A.start_time as start_time')
                      ->whereIn('B.volunteer_id', $asv_ids)
                      ->where(function($query){ $query->where('status','approved')->orWhere('status','attended');})
                      ->where('start_time','>=',$this->year_time)
                      ->orderBy('start_time','DESC')
                      ->orderBy('B.volunteer_id','ASC')->get();
        }

        $start_time = 0;
        $volunteer_id = 0;
        $id = 0;
        foreach ($attended as $event => $object) {
          if($start_time == $object->start_time && $volunteer_id == $object->volunteer_id){
            unset($attended[$id]);
            $start_time = $object->start_time;
            $volunteer_id = $object->volunteer_id;
            $id++;
            // var_dump($attended);
            // echo '<br/><br/>';
          }
          else{
            $start_time = $object->start_time;
            $volunteer_id = $object->volunteer_id;
            $id++;
          }
        }

        // return $attended;

        return View::make('attendance.asv-attendance')->with('attended',$attended)->with('timeline',$timeline)->with('wingman_id',$user_id)->with('date',$previousDate);

    }


}

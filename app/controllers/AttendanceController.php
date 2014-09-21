<?php

class AttendanceController extends BaseController
{

	public function show($user_id)
    {
    	$wingmans_kids = Wingman::find($user_id)->student()->get();

    	$student_ids = array();
    	foreach($wingmans_kids as $wk) 
    		$student_ids[] = $wk->id;

        $attended = CalendarEvent::whereIn('student_id', $student_ids)->where('status','approved')->orWhere('status','attended')->get();

        return View::make('attended-list')->with('attended',$attended);
    }

    public function save($user_id) 
    {
        $attendance_data = Input::get('attended');
        $calender_data = Input::get('calender_entry');

        foreach ($calender_data as $id => $value) {
            $calender_event = CalendarEvent::find($id);
            $calender_event->status = isset($attendance_data[$id]) ? 'attended' : 'approved';
            $calender_event->save();
        }

        return Redirect::to(URL::to('/') . "/attendance/" . $user_id)->with('success', 'Attendence Saved.');
    }


}

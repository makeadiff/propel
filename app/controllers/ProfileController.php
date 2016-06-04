<?php

class ProfileController extends BaseController
{
	public function childProfileIndex($child_id){
		//echo $child_id;
		$student = Student::find($child_id);
		$wingman = $student->wingman()->get();
		$student_data = Student::find($child_id);

		//return $student_data;

        return View::make('profile.child-profile')->with('student',$student)->with('wingman',$wingman[0]); 
	}

	public function childWingmanModules($student_id){
		$student = Student::find($student_id);
		$tables = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','B.calendar_event_id','=','A.id')->join('propel_wingmanModules as C','C.id','=','B.wingman_module_id');

		$wingmanModules = $tables->select('A.start_time as time','C.name as name','C.id')->where('A.status','=','attended')->distinct('B.wingman_id')->where('A.start_time','<=',date('c'))->orderby('C.id','ASC')->where('A.student_id',$student_id	)->get();

		$wingman = $student->wingman()->get();

		//return $wingmanModules;

		return View::make('profile.modules')->with('wingmanModules',$wingmanModules)->with('child',$student)->with('wingman',$wingman);
	}
}
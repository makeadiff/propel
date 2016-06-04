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

	
}
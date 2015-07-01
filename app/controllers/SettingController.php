<?php

class SettingController extends BaseController
{

	public function selectSubjects()
    {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;

        $selected_subjects = City::find($city_id)->subject()->get();

        $selected_subjects_id = array();
        foreach($selected_subjects as $sub)
            $selected_subjects_id[] = $sub->id;

        $all_subjects = Subject::all();

        return View::make('settings/select-subjects')->with('selected_subjects_id',$selected_subjects_id)->with('all_subjects',$all_subjects);
    }

    public function saveSubjects() {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;
        $city = City::find($city_id);

        $selected_subjects = Input::get("subjects");

        $city->subject()->sync($selected_subjects);
        
        return Redirect::to(URL::to('/') . "/settings/subjects")->with('success', 'Subjects Set.');
    }

    //Fellow select Wingman whose students are to be assigned

    public function selectWingman()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $wingmen = $fellow->wingman()->get();

        return View::make('settings/select-wingman')->with('wingmen',$wingmen);
    }


    public function selectWingmen()
    {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;

        $selected_wingmen = Fellow::find($user_id)->wingman()->get();

        
        $all_wingmen = DB::table('User as A')->join('City as B','A.city_id','=','B.id')->join('UserGroup as C','A.id','=','C.user_id')->select('A.id as id','A.name as name','A.phone as phone')->distinct()->where('B.id','=',$city_id)->where('C.group_id','=',348)->where('A.status','=',1)->get();

        foreach ($all_wingmen as $wingman) {
            foreach ($selected_wingmen as $selected) {
                if($wingman->id == $selected->id){
                    $wingman->phone="checked";
                }
            }                
        }            


        return View::make('settings/select-wingmen')->with('selected_wingmen',$selected_wingmen)->with('all_wingmen',$all_wingmen);
    }

    public function saveWingmen() {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $selected_wingmen = Input::get("wingmen");
        //return $selected_wingmen;
        $fellow->wingman()->sync($selected_wingmen);
        
        return Redirect::to(URL::to('/') . "/settings/wingmen")->with('success', 'Wingmen Set.');
    }


    public function selectStudents()
    {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;

        $selected_student = Wingman::find($user_id)->student()->get();

        $selected_student_id = array();
        foreach($selected_student as $student)
            $selected_student_id[] = $student->id;

        $all_centers = City::find($city_id)->center()->get();
        $all_students = array();
        foreach($all_centers as $center) {
            $students = $center->student()->lists('name', 'id');
            foreach ($students as $key => $value) $all_students[$key] = $value;
        }
        
        return View::make('settings/select-students')->with('selected_student_id',$selected_student_id)->with('all_students',$all_students)->with('selected_student',$selected_student);
    }

    //Fellow selects Wingman's students

    public function selectWingmanStudents($wingman_id)
    {
        $user_id = $wingman_id;
        $city_id = Volunteer::find($user_id)->city_id;
        $wingman = Wingman::where('id','=',$user_id)->first();

        $selected_student = DB::table('propel_student_wingman as A')->join('Student as B','B.id','=','A.student_id')->join('Center as C','C.id','=','B.center_id')->select('A.student_id as id','B.name as student_name','C.name as center_name')->where('A.wingman_id','=',$user_id)->get();

        $student_list = DB::table('Student as A')->join('Center as D','D.id','=','A.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as id','A.name as name','D.name as center_name','A.description as grade')->distinct()->where('E.id',$city_id)->where('D.status','=','1')->orderBy('A.name','ASC')->get();

        foreach ($student_list as $student) {
            foreach ($selected_student as $selected) {
                if($student->id == $selected->id){
                    $student->grade="checked";
                }
            }
        }

        return View::make('settings/select-students')->with('selected_student',$selected_student)->with('wingman',$wingman)->with('student_list',$student_list);
    }

    public function saveStudents() {
        $user_id = $_SESSION['user_id'];
        $wingmen = Wingman::find($user_id);

        $selected_students = Input::get("students");
        return $selected_students;
        $wingmen->student()->sync($selected_students);
        
        return Redirect::to(URL::to('/') . "/settings/students")->with('success', 'Students Set');
    }


    public function saveWingmanStudents($wingman_id) {
        $user_id = $wingman_id;
        $wingmen = Wingman::find($user_id);
        
        $selected_students = Input::get("students");
        $wingmen->student()->sync($selected_students);
        
        return Redirect::to(URL::to('/') . "/settings/". $wingman_id . "/students")->with('success', 'Students Set');
    }
}
// dd(DB::getQueryLog());

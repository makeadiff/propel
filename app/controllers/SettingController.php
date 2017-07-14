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

        $wingmen = $fellow->wingman()->where('status','=','1')->where('user_type','=','volunteer')->get();

        return View::make('settings/select-wingman')->with('wingmen',$wingmen);
    }


    public function selectWingmen()
    {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;

        $selected_wingmen = Fellow::find($user_id)->wingman()->get();

        $all_wingmen = DB::table('User as A')
												->join('City as B','A.city_id','=','B.id')
												->join('UserGroup as C','A.id','=','C.user_id')
												->select('A.id as id','A.name as name','A.phone as phone','C.group_id as group_id')
												->distinct()
												->where('B.id','=',$city_id)
												->wherein('C.group_id',[348,365])
												->where('A.status','=',1)
												->where('A.user_type','=','volunteer')->get();

        foreach ($all_wingmen as $wingman) {
					if(count($selected_wingmen)!= 0){
            foreach ($selected_wingmen as $selected) {

                if($wingman->id == $selected->id){
                    $wingman->phone = "checked";
                }
                if($wingman->group_id == 365){
                    $wingman->group_id = "| Aftercare";
                }
                elseif($wingman->group_id == 348){
                    $wingman->group_id = " ";
                }
            }
					}
					else{
						if($wingman->group_id == 365){
								$wingman->group_id = "(Aftercare)";
						}
						elseif($wingman->group_id == 348){
								$wingman->group_id = " ";
						}
					}
        }

        return View::make('settings/select-wingmen')->with('selected_wingmen',$selected_wingmen)->with('all_wingmen',$all_wingmen);
    }

    public function saveWingmen() {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $selected_wingmen = Input::get("wingmen");
        if(!empty($selected_wingmen)){
            $fellow->wingman()->sync($selected_wingmen);
        }
        else{
            DB::table('propel_fellow_wingman')->where('fellow_id','=',$user_id)->delete();
        }
        return Redirect::to(URL::to('/') . "/settings/wingmen")->with('success', 'Wingmen Set.');
    }



    public function selectStudents($wingman_id)
    {
        $user_id = $wingman_id;
        $city_id = Volunteer::find($user_id)->city_id;
        $wingman = Wingman::where('id','=',$user_id)->first();

				// return $this->get_year();

        $selected_student = DB::table('propel_student_wingman as A')
														->join('Student as B','B.id','=','A.student_id')
														->join('Center as C','C.id','=','B.center_id')
														->leftJoin('StudentLevel','StudentLevel.student_id','=','B.id')
														->leftJoin('Level','StudentLevel.level_id','=','Level.id')
														->select('A.student_id as id','B.name as student_name','C.name as center_name','Level.grade as grade')
														->distinct()
														->where('A.wingman_id','=',$user_id)
														->where('Level.year','=',$this->get_year())
														->get();

        // return $selected_student;

        $student_list = DB::table('Student as A')
														->join('Center as D','D.id','=','A.center_id')
														->join('City as E','E.id','=','D.city_id')
														->join('StudentLevel','StudentLevel.student_id','=','A.id')
														->join('Level','StudentLevel.level_id','=','Level.id')
														->select('A.id as id','A.name as name','D.name as center_name','Level.grade as grade')
														->distinct()
														->where('E.id',$city_id)
														->where('D.status','=','1')
														->where('Level.year','=',$this->get_year())
														->where('Level.grade','>=',10)
														->orderBy('A.name','ASC')->get();

        foreach ($student_list as $student) {
            foreach ($selected_student as $selected) {
                if($student->id == $selected->id){
                    $student->grade="checked";
                }
            }
        }

        return View::make('settings/select-students')->with('selected_student',$selected_student)->with('wingman',$wingman)->with('student_list',$student_list);
    }


    public function saveStudents($wingman_id) {
        $user_id = $wingman_id;
        $wingmen = Wingman::find($user_id);

				$selected_students = Input::get('students');

        if(!empty($selected_students)){
					$wingmen->student()->sync($selected_students);
					// DB::table('propel_student_wingman')->where('wingman_id','=',$wingman_id)->delete();
					// foreach ($selected_students as $students) {
					// 	$datetime = new DateTime(date('c'));
					// 	DB::table('propel_student_wingman')->insert(
					// 		['student_id'=>$students,'wingman_id'=>$wingman_id,'created_at'=>$datetime,'updated_at'=>$datetime]
					// 	);
					// }
        }
        else{
          DB::table('propel_student_wingman')->where('wingman_id','=',$wingman_id)->delete();
        }

        return Redirect::to(URL::to('/') . "/settings/". $wingman_id . "/students")->with('success', 'Students Set');
    }
}
// dd(DB::getQueryLog());

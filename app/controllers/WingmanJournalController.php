<?php

class WingmanJournalController extends BaseController
{

	public function showList($user_id)
    {
        $entries = WingmanJournal::where('wingman_id','=',$user_id)->get();
        $session_id = $_SESSION['user_id'];
        $user = Volunteer::find($session_id);

        $groups = $user->group()->get();

        foreach($groups as $group) {
            if($group->name == 'Propel Multiplier')
                $user_group = 'Propel Fellow';
            elseif($group->name == 'Propel Wingman')
                $user_group = 'Propel Wingman';
            elseif($group->name == 'Propel Strat')
                $user_group = 'Propel Strat';
            elseif($group->name == 'Program Director, Propel')
                $user_group = 'Program Director, Propel';
        }

        return View::make('wingman-journal',['entries'=>$entries,'user_group'=>$user_group]);
    }

    public function selectWingman()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $wingmen = $fellow->wingman()->get();

        return View::make('select-wingman')->with('wingmen',$wingmen);
    }

    public function selectStudentsCity()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);
        $city = $fellow->city()->first();
        //return $city->id;
        $students = DB::table('propel_student_wingman as A')->join('Student as B','A.student_id','=','B.id')->join('Center as C','C.id','=','B.center_id')->join('City as D','D.id','=','C.city_id')->select('A.student_id','B.name','A.wingman_id')->distinct()->where('D.id','=',$city->id)->get();
        //return $students;
        return View::make('feedback/select-students-city')->with('students',$students);
    }

    public function showStudents($wingman_id)
    {
        $students = Wingman::find($wingman_id)->student()->get();
        return View::make('feedback/show-students')->with('students',$students)->with('wingman_id',$wingman_id);
    }

    public function showFeedback($wingman_id,$student_id)
    {
        $student = Student::where('id','=',$student_id)->first();
        $entries = DB::table('propel_wingmanJournals as A')->join('User as B','B.id','=','A.wingman_id')->select('A.id as id','A.on_date as on_date','B.name as wingman_name','A.title as title')->distinct()->where('A.student_id','=',$student_id)->where('A.type','=','child_feedback')->get();
        return View::make('feedback/child_feedback')->with('entries',$entries)->with('student',$student);
    }

    public function showModuleFeedback(){
        $modules = WingmanModule::all();
        //return $modules;

        $entries = DB::table('propel_wingmanJournals as A')->join('User as B','B.id','=','A.wingman_id')->join('Student as C','C.id','=','A.student_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as id','A.title as title','B.name as wingman_name','E.name as city_name','A.on_date as on_date','A.module_id as module_id')->distinct()->where('A.type','=','module_feedback')->get();
        return View::make('feedback/module_feedback')->with('entries',$entries)->with('modules',$modules);
    }

}

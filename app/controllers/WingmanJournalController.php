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
            if($group->name == 'Propel Fellow')
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

    public function selectWingmanFeedback()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);
        $wingmen = $fellow->wingman()->get();
        return View::make('feedback/select-wingman')->with('wingmen',$wingmen);
    }

    public function showStudents($wingman_id)
    {
        $students = Wingman::find($wingman_id)->student()->get();
        return View::make('feedback/show-students')->with('students',$students)->with('wingman_id',$wingman_id);
    }

    public function showFeedback($wingman_id,$student_id)
    {
        $student = Student::where('id','=',$student_id)->first();
        $entries = WingmanJournal::where('student_id','=',$student_id)->where('type','=','child_feedback')->get();
        return View::make('feedback/child_feedback')->with('entries',$entries)->with('student',$student);
    }

    public function showModuleFeedback(){
        $modules = WingmanModule::all();
        //return $modules;

        $entries = WingmanJournal::where('type','=','module_feedback')->get();
        return View::make('feedback/module_feedback')->with('entries',$entries)->with('modules',$modules);
    }

}

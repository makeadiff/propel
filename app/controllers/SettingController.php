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


    public function selectWingmen()
    {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;

        $selected_wingmen = Fellow::find($user_id)->wingman()->get();

        $selected_wingmen_id = array();
        foreach($selected_wingmen as $sub)
            $selected_wingmen_id[] = $sub->id;

        $all_wingmen = City::find($city_id)->wingman()->get();

        return View::make('settings/select-wingmen')->with('selected_wingmen_id',$selected_wingmen_id)->with('all_wingmen',$all_wingmen);
    }

    public function saveWingmen() {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $selected_wingmen = Input::get("wingmen");

        $fellow->wingman()->sync($selected_wingmen);
        
        return Redirect::to(URL::to('/') . "/settings/wingmen")->with('success', 'Wingmen Set.');
    }


    // public function selectStudents()
    // {
    //     $user_id = $_SESSION['user_id'];
    //     $city_id = Volunteer::find($user_id)->city_id;

    //     $selected_wingmen = Fellow::find($user_id)->wingman()->get();

    //     $selected_wingmen_id = array();
    //     foreach($selected_wingmen as $sub)
    //         $selected_wingmen_id[] = $sub->id;

    //     $all_wingmen = City::find($city_id)->wingman()->get();

    //     return View::make('settings/select-wingmen')->with('selected_wingmen_id',$selected_wingmen_id)->with('all_wingmen',$all_wingmen);
    // }

    // public function saveWingmen() {
    //     $user_id = $_SESSION['user_id'];
    //     $fellow = Fellow::find($user_id);

    //     $selected_wingmen = Input::get("wingmen");

    //     $fellow->wingman()->sync($selected_wingmen);
        
    //     return Redirect::to(URL::to('/') . "/settings/wingmen")->with('success', 'Wingmen Set.');
    // }


}

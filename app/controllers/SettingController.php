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
        
        return Redirect::to(URL::to('/') . "/settings/subjects")->with('success', 'Sujects Set.');
    }


    public function selectWingman()
    {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;

        $selected_wingmen = City::find($city_id)->wingman()->get();

        $selected_wingmen_id = array();
        foreach($selected_wingmen as $sub)
            $selected_wingmen_id[] = $sub->id;

        $all_wingmen = Wingmen::all();

        return View::make('settings/select-subjects')->with('selected_wingmen_id',$selected_wingmen_id)->with('all_subjects',$all_subjects);
    }

    public function saveWingman() {
        $user_id = $_SESSION['user_id'];
        $city_id = Volunteer::find($user_id)->city_id;
        $city = City::find($city_id);

        $selected_subjects = Input::get("subjects");

        $city->subject()->sync($selected_subjects);
        
        return Redirect::to(URL::to('/') . "/settings/subjects")->with('success', 'Sujects Set.');
    }

    // public function save($user_id) 
    // {
    //     $attendance_data = Input::get('attended');
    //     $calender_data = Input::get('calender_entry');

    //     foreach ($calender_data as $id => $value) {
    //         $calender_event = CalendarEvent::find($id);
    //         $calender_event->status = isset($attendance_data[$id]) ? 'attended' : 'approved';
    //         $calender_event->save();
    //     }

    //     return Redirect::to(URL::to('/') . "/attendance/" . $user_id)->with('success', 'Attendence Saved.');
    // }


}

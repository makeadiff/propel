<?php

class WingmanJournalController extends BaseController
{

	public function showList($user_id)
    {
        $entries = WingmanJournal::where('wingman_id','=',$user_id)->get();

        return View::make('wingman-journal')->with('entries',$entries);
    }

    public function selectWingman()
    {
        $user_id = $_SESSION['user_id'];
        $fellow = Fellow::find($user_id);

        $wingmen = $fellow->wingman()->get();

        return View::make('select-wingman')->with('wingmen',$wingmen);
    }


}

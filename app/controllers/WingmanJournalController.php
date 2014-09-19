<?php

class WingmanJournalController extends BaseController
{

	public function showList($user_id)
    {
        $entries = WingmanJournal::where('wingman_id','=',$user_id)->get();

        return View::make('wingman-journal')->with('entries',$entries);
    }


}

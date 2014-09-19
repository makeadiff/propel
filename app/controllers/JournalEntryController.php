<?php

class JournalEntryController extends \BaseController {


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $_user_id = $_SESSION['user_id'];
        $students = Wingman::find($_user_id)->student()->get();
		return View::make('journal-entry.create')->with('students',$students);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $user_id = $_SESSION['user_id'];
        $je = new WingmanJournal();
        $je->type = Input::get('type');
        $je->title = Input::get('title');
        $je->on_date = date_format(date_create(Input::get('pickdate')),'Y-m-d');
        $je->mom = Input::get('mom');
        $je->wingman_id = $user_id;
        $je->student_id = Input::get('student');
        $je->save();

        return Redirect::to(URL::to('/') . "/wingman-journal/" . $user_id);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $journal_entry = WingmanJournal::find($id);

        return View::make('journal-entry.show')->with('journal_entry',$journal_entry);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$journal_entry = WingmanJournal::find($id);
        $wingman_id = $journal_entry->wingman_id;
        $students = Wingman::find($wingman_id)->student()->get();

        return View::make('journal-entry.edit')->with('journal_entry',$journal_entry)->with('students',$students);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $je = WingmanJournal::find($id);
        $wingman_id = $je->wingman_id;

        $je->type = Input::get('type');
        $je->title = Input::get('title');
        $je->on_date = date_format(date_create(Input::get('pickdate')),'Y-m-d');
        $je->mom = Input::get('mom');
        $je->student_id = Input::get('student');
        $je->save();

        return Redirect::to(URL::to('/') . "/wingman-journal/" . $wingman_id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $je = WingmanJournal::find($id);
        $wingman_id = $je->wingman_id;
        $je->delete();
        return Redirect::to(URL::to('/') . "/wingman-journal/" . $wingman_id);
	}


}

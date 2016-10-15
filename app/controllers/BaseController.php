<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	protected $year_time;

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function __construct(){
        $this_month = intval(date('m'));
        $months = array();
        $start_date = '01';
        $start_month = '05'; // May
        $start_year = date('Y');
        if($this_month < $start_month) $start_year = date('Y')-1;
        $this->year_time = date('Y-m-d 00:00:00',strtotime($start_year.'-'.$start_month.'-'.$start_date));
        return View::share('year_time',$this->year_time);
    }

}

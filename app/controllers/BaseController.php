<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	protected $year_time;
	protected $start_year;

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
        $start_month = '06'; // June
        $start_year = date('Y');
        if($this_month < $start_month) $start_year = date('Y')-1;
        $this->year_time = date('Y-m-d 00:00:00',strtotime($start_year.'-'.$start_month.'-'.$start_date));
        return View::share('year_time',$this->year_time);
  }

	public function get_year() { /* Function get_year(): Source: madapp/system/helper/misc_helper.php Line 123 */
			$this_month = intval(date('m'));
			$months = array();
			$start_month = 5; // May - Temporarily changed to August
			$start_year = date('Y');
			if($this_month < $start_month) $start_year = date('Y')-1;
			return $start_year;
	}


}

<?php

class ReportController extends BaseController
{

    private $asvGroupName = "Propel ASV";

    public function showCities()
    {
        $cities = City::all();

        return View::make('reports.class-status.select-city')
                      ->with('cities',$cities);
    }

    public function showReports($start = null,$end = null){
        $cities = DB::table('City')
                    ->where('id','<',26)
                    ->orderBy('name','ASC')
                    ->get();

        $wingmen = DB::table('User as A')
                    ->join('UserGroup as B','A.id','=','B.user_id')
                    ->join('City as C','C.id','=','A.city_id')
                    ->select('A.id as wingman_id','A.name as wingman_name','C.id as city_id','C.name as city_name')
                    ->distinct()
                    ->where('B.group_id','=','348')
                    ->where('C.id','<','26')
                    ->where('A.status','=',1)
                    ->where('A.user_type','=','volunteer')
                    ->orderBy('A.city_id','ASC')
                    ->orderBy('A.name','ASC')
                    ->get();

        $adopted_wingman = 0;
        $total_wingman = count($wingmen);

        //Wingman Adoption Calculation

        foreach ($wingmen as $wingman) {
          $wingman_id = $wingman->wingman_id;

          $journals = DB::table('propel_wingmanJournals as A')
                      ->where('wingman_id','=',$wingman_id)
                      ->where('type','=','child_feedback');

          //Check for start_date filter value;
          if(isset($start) && $start!=null){
            $start_time = date('Y-m-d 00:00:00',strtotime($start));
          }
          else{
            $start_time = $this->year_time;
          }
          $journals = $journals->where('on_date','>=',$start_time);

          //Check for end_date filter value;
          if(isset($end) && $end!=null){
            $end_time = date('Y-m-d 00:00:00',strtotime($end));
          }
          else{
            $end_time = date('Y-m-d 00:00:00');
          }

          $journals = $journals->where('on_date','<=',$end_time);

          $journals = $journals->get();
          $count = count($journals);

          $start_date = date_create($start_time);
          $end_date = date_create($end_time);

          $difference = date_diff($end_date,$start_date);
          $ideal_count = $difference->format('%d')%7;

          if($count >= $ideal_count){
            $adopted_wingman+= 1;
          }
          else{
            $adopted_wingman+= (float) $count/$ideal_count;
          }
          //echo $wingman->wingman_name.' '.$wingman->city_name.' '.$ideal_count.' '.$count.PHP_EOL;
        }


        //Propel Wingman Adoption

        $propel_fellows = DB::table('User as A')
                          ->join('City as C','C.id','=','A.city_id')
                          ->join('UserGroup as D','D.user_id','=','A.id')
                          ->select('A.id as fellow_id',
                                    'A.name as fellow_name',
                                    'C.id as city_id',
                                    'C.name as city_name')
                          ->distinct()
                          ->where('A.user_type','=','volunteer')
                          ->where('A.status','=','1')
                          ->where('D.group_id','=','272')
                          ->where('C.id','<=','25')
                          ->orderby('A.name','ASC')
                          ->orderby('C.name','ASC')
                          ->get();

        $adopted_fellow = 0;
        $total_fellow = count($propel_fellows);

        foreach ($propel_fellows as $fellow) {
          $fellow_id = $fellow->fellow_id;
          $approved_count = 0;

          $table = DB::table('propel_fellow_wingman as A');
          //$fellow_wingman_count = count($table->where('A.fellow_id','=',$fellow_id)->get());
          //echo $fellow_wingman_count;

          $fellow_data = $table->join('propel_student_wingman as B','B.wingman_id','=','A.wingman_id')
                      ->leftJoin('propel_calendarEvents as C','B.student_id','=','C.student_id')
                      ->select('A.fellow_id as fellow_id',
                                'B.wingman_id as wingman_id',
                                'B.student_id as student_id',
                                'C.start_time as start_time',
                                'C.status as status',
                                DB::raw('MONTH(C.start_time) as month'))
                      ->distinct()
                      ->where('A.fellow_id','=',$fellow_id)
                      ->where('C.status','<>','cancelled')
                      ->groupby(DB::raw('MONTH(C.start_time)'))
                      ->groupby('B.wingman_id')
                      //->groupby('C.status')
                      ->orderBy('A.wingman_id')
                      ->orderBy(DB::raw('MONTH(C.start_time)'),'ASC')
                      ->orderBy('C.status','DESC');

          //Check for start_date filter value;
          if(isset($start) && $start!=null){
            $start_time = date('Y-m-d 00:00:00',strtotime($start));
          }
          else{
            $start_time = $this->year_time;
          }

          $fellow_data = $fellow_data->where('start_time','>=',$start_time);

          //Check for end_date filter value;
          if(isset($end) && $end!=null){
            $end_time = date('Y-m-d 00:00:00',strtotime($end));
          }
          else{
            $end_time = date('Y-m-d 00:00:00');
          }

          $fellow_data = $fellow_data->where('end_time','<=',$end_time);

          $fellow_data = $fellow_data->get();

          if(count($fellow_data)!=0){
            $wingman_id = 0;
            $month = 0;
            $approved = 0;
            foreach ($fellow_data as $data) {
              if($data->status=='approved'||$data->status=='attended')
                    $approved++;
            }
            // echo count($fellow_data).' '.$approved.' |'.PHP_EOL;
            $adopted_fellow += (float)$approved/count($fellow_data);
          }
          else {
            //$approved_count
          }


          // if(count($fellow_data)!=0)
          // return $fellow_data;
        }

        //return count($propel_fellows);

        $wingmen_adoption = (float)($adopted_wingman/$total_wingman*100);
        $fellow_adoption = (float)($adopted_fellow/$total_fellow*100);

        return View::make('reports.select-report')->with('wingmen_adoption',$wingmen_adoption)->with('fellow_adoption',$fellow_adoption);
    }

    public function showWingmanJournalReport() {
        $report = DB::select('SELECT wj.id as ID, wj.type as Type, wj.title as Title, wj.mom as Minutes, wj.on_date as OnDate,
                                User.name as Wingman, Student.name as Student, Center.name as Center, City.name as City,
                                DATE(wj.created_at) as CreatedOn  FROM `propel_wingmanJournals` AS wj
                                INNER JOIN User
                                ON User.id = wj.wingman_id
                                INNER JOIN Student
                                ON Student.id = wj.student_id
                                INNER JOIN City
                                ON City.id = User.city_id
                                INNER JOIN Center
                                ON Center.id = Student.center_id');

        $report = json_decode(json_encode($report),true);

        Excel::create('Wingman-Journal-Report', function($excel) use($report) {
                $excel->sheet('Report', function($sheet) use($report)  {
                $sheet->fromArray($report);
              });
            })->export('csv');
    }


    public function attendanceHome(){
        return View::make('reports.attendance.home');
    }


    public function attendanceReport(){

        $start = "/null";
        $end = "/null";
        $city_id = "/null";
        $event_type = '/'.Input::get('event_type');
        if(Input::get('city')!=""){
            $city_id = '/'.Input::get('city');
        }
        if(Input::get('start_date')!=""){
            $start = '/'.Input::get('start_date');
        }
        if(Input::get('end_date')!=""){
            $end = '/'.Input::get('end_date');
        }

        return Redirect::away(URL::to('/reports/attendance-report').$city_id.$event_type.$start.$end);
    }

    public function showAttendanceReport($city_id = null,$event_type = null,$start_date = null, $end_date = null) {

        $cities = DB::table('City')->where('id','<',26)->orderBy('name','ASC')->get();

        if(isset($event_type) && $event_type=="wingman_time"){

            if($city_id =='null'){
                //Filter data for cities for wingman_time

                $tables = DB::table('propel_calendarEvents as A')
                              ->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')
                              ->join('User as C','C.id','=','B.wingman_id')
                              ->join('City as D','D.id','=','C.city_id');

                $query = $tables->select('C.id','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(D.id)'))
                                ->groupby('D.id')
                                ->groupby('A.status')
                                ->where('A.status','<>','cancelled')
                                ->where('A.status','<>','created')
                                ->where('D.id','<',26);

                if(isset($start_date) && $start_date!='null'){
                    $start = date('Y-m-d 00:00:00',strtotime($start_date));
                    $query = $query->where('A.start_time','>=',$start);
                }
                else{
                    $start = date('Y-m-d 00:00:00',strtotime($this->year_time));
                    $query = $query->where('A.start_time','>=',$start);
                }

                if(isset($end_date) && $end_date!='null'){
                    $end = date('Y-m-d 00:00:00',strtotime($end_date));
                    $query = $query->where('A.end_time','<=',$end);
                }
                else{
                  $end = date('Y-m-d h:i:s');
                  $query = $query->where('A.end_time','<=',$end);
                }

                $start_day = new DateTime($start);
                $end_day = new DateTime($end);
                $duration =  $end_day->diff($start_day)->format("%a");
                $ideal_session = floor($duration/7);

                $data_collection = $query->get();

                $datas = array();

                $id = 0;

                foreach ($data_collection as $data) {
                    if($data->id!=$city_id){
                        $id = $data->city_id;
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                    }
                    else{
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                        $id = $data->city_id;
                    }
                }

                $citydetails_table = DB::table('Student as A')
                                  ->join('propel_student_wingman as B','B.student_id','=','A.id')
                                  ->join('User as E','E.id','=','B.wingman_id')
                                  ->join('Center as C','C.id','=','A.center_id')
                                  ->join('City as D','D.id','=','C.city_id');

                $citydetails = $citydetails_table->select('D.id','D.name')->distinct()->where('D.id','<>',26)->orderBy('D.name','ASC')->get();

                foreach ($citydetails as $city){
                  $id = $city->id;
                  $datas[$id]['city_id'] = $city->id;
                  $datas[$id]['city_name'] = $city->name;
                  if(!isset($datas[$id]['approved']))
                    $datas[$id]['approved'] = 0;

                  if(!isset($datas[$id]['attended']))
                    $datas[$id]['attended'] = 0;

                  $citydetails = DB::table('Student as A')
                                    ->join('propel_student_wingman as B','B.student_id','=','A.id')
                                    ->join('User as E','E.id','=','B.wingman_id')
                                    ->join('Center as C','C.id','=','A.center_id')
                                    ->join('City as D','D.id','=','C.city_id');

                  $child = $citydetails->select('A.id','A.name')
                            ->where('D.id','=',$id)
                            ->where('E.status','=','1')
                            ->where('E.user_type','=','volunteer')->get();

                  // echo $id.'-'.count($child).'<br/>';
                  $datas[$id]['child_count'] = count($child);;
                  $datas[$id]['ideal_session'] = count($child)*$ideal_session;
                }

                return View::make('reports.attendance.attendance-report')
                        ->with('datas',$datas)
                        ->with('event_type',$event_type)
                        ->with('start_date',$start_date)
                        ->with('end_date',$end_date)
                        ->with('ideal_session',$ideal_session);
            }
            else{
                //Filter data for Wingman in a city for wingman_time

                $tables = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.wingman_id')->join('City as D','D.id','=','C.city_id');

                $query = $tables->select('C.id as wingman_id','C.name as wingman_name','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(C.id)'))->groupby('C.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26)->where('D.id',$city_id)->orderby('D.name','ASC');

                if(isset($start_date) && $start_date!='null'){
                    $start = date('Y-m-d 00:00:00',strtotime($start_date));
                    $query = $query->where('A.start_time','>=',$start);
                }
                else{
                    $start = date('Y-m-d 00:00:00',strtotime($this->year_time));
                    $query = $query->where('A.start_time','>=',$start);
                }

                if(isset($end_date) && $end_date!='null'){
                    $end = date('Y-m-d 00:00:00',strtotime($end_date));
                    $query = $query->where('A.end_time','<=',$end);
                }
                else{
                  $end = date('Y-m-d h:i:s');
                  $query = $query->where('A.end_time','<=',$end);
                }

                $start_day = new DateTime($start);
                $end_day = new DateTime($end);
                $duration =  $end_day->diff($start_day)->format("%a");
                $ideal_session = floor($duration/7);

                $data_collection = $query->get();

                $datas = array();
                $id = 0;

                $wingmandetails_table = DB::table('Student as A')
                                  ->join('propel_student_wingman as B','B.student_id','=','A.id')
                                  ->join('User as E','E.id','=','B.wingman_id')
                                  ->join('Center as C','C.id','=','A.center_id')
                                  ->join('City as D','D.id','=','C.city_id');

                $wingmandetails = $wingmandetails_table->select('E.id','E.name')->distinct()->where('D.id','<>',26)->orderBy('D.name','ASC')->get();

                foreach ($data_collection as $data) {

                    if($data->wingman_id!=$id){
                        $id = $data->wingman_id;

                        $datas[$id]['wingman_name'] = $data->wingman_name   ;
                        $datas[$id]['city_name'] = $data->city_name;
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                    }
                    else{
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                        $id = $data->wingman_id;
                    }
                }

                $wingmandetails_table = DB::table('Student as A')
                                  ->join('propel_student_wingman as B','B.student_id','=','A.id')
                                  ->join('User as E','E.id','=','B.wingman_id')
                                  ->join('Center as C','C.id','=','A.center_id')
                                  ->join('City as D','D.id','=','C.city_id');

                $wingmandetails = $wingmandetails_table->select('E.id','E.name')
                                    ->where('E.status','=','1')
                                    ->where('E.user_type','=','volunteer')
                                    ->where('D.id','=',$city_id)
                                    ->orderBy('D.name','ASC')->get();

                foreach ($wingmandetails as $wingman){
                  $id = $wingman->id;;
                  $datas[$id]['wingman_name'] = $wingman->name;
                  if(!isset($datas[$id]['approved']))
                    $datas[$id]['approved'] = 0;

                  if(!isset($datas[$id]['attended']))
                    $datas[$id]['attended'] = 0;

                  $wingmandetails = DB::table('Student as A')
                                    ->join('propel_student_wingman as B','B.student_id','=','A.id')
                                    ->join('User as E','E.id','=','B.wingman_id')
                                    ->join('Center as C','C.id','=','A.center_id')
                                    ->join('City as D','D.id','=','C.city_id');

                  $child = $wingmandetails->select('A.id','A.name')
                            ->where('E.id','=',$id)
                            ->where('E.status','=','1')
                            ->where('D.id','=',$city_id)
                            ->where('E.user_type','=','volunteer')->get();

                  $datas[$id]['child_count'] = count($child);;
                  $datas[$id]['ideal_session'] = count($child)*$ideal_session;
                }


                return View::make('reports.attendance.city-attendance-report')->with('datas',$datas)->with('event_type',$event_type)->with('cities',$cities)->with('city_id',$city_id)->with('start_date',$start_date)->with('end_date',$end_date)->with('ideal_session',$ideal_session);
            }
        }
        else if(isset($event_type) && $event_type=="volunteer_time"){

            if($city_id =='null'){
                //Filter data for cities for volunteer_time

                $tables = DB::table('propel_calendarEvents as A')->join('propel_volunteerTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.volunteer_id')->join('City as D','D.id','=','C.city_id');

                $query = $tables->select('C.id','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(D.id)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26)->orderby('D.name','ASC');

                if(isset($start_date) && $start_date!='null'){
                    $start = date('Y-m-d 00:00:00',strtotime($start_date));
                    $query = $query->where('A.start_time','>=',$start);
                }
                else{
                    $start = date('Y-m-d 00:00:00',strtotime($this->year_time));
                    $query = $query->where('A.start_time','>=',$start);
                }

                if(isset($end_date) && $end_date!='null'){
                    $end = date('Y-m-d 00:00:00',strtotime($end_date));
                    $query = $query->where('A.end_time','<=',$end);
                }
                else{
                  $end = date('Y-m-d h:i:s');
                  $query = $query->where('A.end_time','<=',$end);
                }

                $start_day = new DateTime($start);
                $end_day = new DateTime($end);
                $duration =  $end_day->diff($start_day)->format("%a");
                $ideal_session = floor($duration/7);

                $data_collection = $query->get();

                $datas = array();
                $id = 0;

                foreach ($data_collection as $data) {

                    if($data->id!=$city_id){
                        $id = $data->city_id;

                        $datas[$id]['city_name'] = $data->city_name;
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                    }
                    else{
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                        $id = $data->city_id;
                    }
                }

                $citydetails_table = DB::table('Student as A')
                                  ->join('propel_student_wingman as B','B.student_id','=','A.id')
                                  ->join('User as E','E.id','=','B.wingman_id')
                                  ->join('Center as C','C.id','=','A.center_id')
                                  ->join('City as D','D.id','=','C.city_id');

                $citydetails = $citydetails_table->select('D.id','D.name')->distinct()->where('D.id','<>',26)->orderBy('D.name','ASC')->get();

                foreach ($citydetails as $city){
                  $id = $city->id;
                  $datas[$id]['city_id'] = $city->id;
                  $datas[$id]['city_name'] = $city->name;
                  if(!isset($datas[$id]['approved']))
                    $datas[$id]['approved'] = 0;

                  if(!isset($datas[$id]['attended']))
                    $datas[$id]['attended'] = 0;

                  $citydetails = DB::table('Student as A')
                                    ->join('propel_student_wingman as B','B.student_id','=','A.id')
                                    ->join('User as E','E.id','=','B.wingman_id')
                                    ->join('Center as C','C.id','=','A.center_id')
                                    ->join('City as D','D.id','=','C.city_id');

                  $child = $citydetails->select('A.id','A.name')
                            ->where('D.id','=',$id)
                            ->where('E.status','=','1')
                            ->where('E.user_type','=','volunteer')->get();

                }

                return View::make('reports.attendance.attendance-report')->with('datas',$datas)->with('event_type',$event_type)->with('start_date',$start_date)->with('end_date',$end_date);
            }
            else{
                //Filter data for volunteers in a city for volunteer_time

                $tables = DB::table('propel_calendarEvents as A')->join('propel_volunteerTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.volunteer_id')->join('City as D','D.id','=','C.city_id');

                $query = $tables->select('C.id as asv_id','C.name as asv_name','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(C.id)'))->groupby('C.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26)->where('D.id',$city_id)->orderby('D.name','ASC');

                if(isset($start_date) && $start_date!='null'){
                    $start = date('Y-m-d 00:00:00',strtotime($start_date));
                    $query = $query->where('A.start_time','>=',$start);
                }
                else{
                    $start = date('Y-m-d 00:00:00',strtotime($this->year_time));
                    $query = $query->where('A.start_time','>=',$start);
                }

                if(isset($end_date) && $end_date!='null'){
                    $end = date('Y-m-d 00:00:00',strtotime($end_date));
                    $query = $query->where('A.end_time','<=',$end);
                }
                else{
                  $end = date('Y-m-d h:i:s');
                  $query = $query->where('A.end_time','<=',$end);
                }

                $start_day = new DateTime($start);
                $end_day = new DateTime($end);
                $duration =  $end_day->diff($start_day)->format("%a");
                $ideal_session = floor($duration/7);

                $data_collection = $query->get();

                $datas = array();
                $id = 0;

                foreach ($data_collection as $data) {

                    if($data->asv_id!=$id){
                        $id = $data->asv_id;

                        $datas[$id]['asv_name'] = $data->asv_name;
                        $datas[$id]['city_name'] = $data->city_name;
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                    }
                    else{
                        if($data->status == 'approved'){
                            $datas[$id]['approved'] = $data->event_count;
                        }
                        if($data->status == 'attended'){
                            $datas[$id]['attended'] = $data->event_count;
                        }

                        $id = $data->asv_id;
                    }
                }

                $asvdetails_table = DB::table('User as A')
                                  ->join('UserGroup as B','B.user_id','=','A.id')
                                  ->join('City as C','C.id','=','A.city_id');

                $asvdetails = $asvdetails_table->select('A.id','A.name')
                                    ->where('A.status','=','1')
                                    ->where('A.user_type','=','volunteer')
                                    ->where('B.group_id','=',349)
                                    ->where('C.id','=',$city_id)
                                    ->orderBy('A.name','ASC')->get();

                foreach ($asvdetails as $asv){
                  $id = $asv->id;;
                  $datas[$id]['asv_name'] = $asv->name;
                  if(!isset($datas[$id]['approved']))
                    $datas[$id]['approved'] = 0;

                  if(!isset($datas[$id]['attended']))
                    $datas[$id]['attended'] = 0;
                }

                return View::make('reports.attendance.city-attendance-report')->with('datas',$datas)->with('event_type',$event_type)->with('cities',$cities)->with('city_id',$city_id)->with('start_date',$start_date)->with('end_date',$end_date);
            }

        }
        //Academic Support Volunteer Session

    }

    public function showClassStatus($city_id)
    {
        $city = City::find($city_id);

        $wingmen = Group::where('name','=','Propel Wingman')->first()->wingman()->where('city_id','=',$city_id)->get();
        $volunteers = Group::where('name','=','Propel Volunteer')->first()->volunteer()->where('city_id','=',$city_id)->get();

        foreach($wingmen as $wingman) {
            $time = $wingman->wingmanTime()->get();
            $count = 0;
            foreach($time as $t) {
                $ce = $t->calendarEvent()->first();
                if($ce->status == 'approved' || $ce->status == 'attended') {
                    $count++;
                }
            }
            $wingman->total_classes = $count;
        }

        foreach($volunteers as $volunteer) {
            $time = $volunteer->volunteerTime()->get();
            $count = 0;
            foreach($time as $t) {
                $ce = $t->calendarEvent()->first();
                if($ce->status == 'approved' || $ce->status == 'attended') {
                    $count++;
                }
            }
            $volunteer->total_classes = $count;
        }

        foreach($wingmen as $wingman) {
            $time = $wingman->wingmanTime()->get();
            $count = 0;
            foreach($time as $t) {
                $ce = $t->calendarEvent()->first();
                if($ce->status == 'attended') {
                    $count++;
                }
            }
            $wingman->classes_attended = $count;
        }

        foreach($volunteers as $volunteer) {
            $time = $volunteer->volunteerTime()->get();
            $count = 0;
            foreach($time as $t) {
                $ce = $t->calendarEvent()->first();
                if($ce->status == 'attended') {
                    $count++;
                }
            }
            $volunteer->classes_attended = $count;
        }

        return View::make('reports.class-status.report')->with('wingmen',$wingmen)->with('volunteers',$volunteers);

    }

    public function showCancellationReport(){

        //return 'In';

        $cities = City::where('id','<=','25')
                  ->orderby('name','ASC')
                  ->get();

        $tables = DB::table('propel_calendarEvents as A')
                  ->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')
                  ->join('Student as C','C.id','=','A.student_id')
                  ->join('Center as D','D.id','=','C.center_id')
                  ->join('City as E','E.id','=','D.city_id')
                  ->join('propel_student_wingman as F','F.student_id','=','C.id')
                  ->join('User as G','G.id','=','F.wingman_id');

        $total_classes = DB::table('propel_calendarEvents as A')
                  ->join('Student as C','C.id','=','A.student_id')
                  ->join('propel_student_wingman as F','F.student_id','=','C.id')
                  ->join('User as G','G.id','=','F.wingman_id')
                  ->join('Center as D','D.id','=','C.center_id')
                  ->join('City as E','E.id','=','D.city_id')
                  ->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as   city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')
                  ->distinct()
                  ->orderby('E.name','ASC')
                  ->where('A.status','<>','created')
                  ->get();

        $cancelled_classes = $tables->select('A.id as event_id',
                                              'A.type as event_type',
                                              'B.comment as comment',
                                              'B.reason as reason',
                                              'B.updated_at as cancelled_time',
                                              'C.name as student_name',
                                              'C.id as student_id',
                                              'D.name as center_name',
                                              'D.id as center_id',
                                              'E.name as city_name',
                                              'E.id as city_id',
                                              'A.start_time as start_time',
                                              'A.end_time as end_time',
                                              'G.name as wingman_name',
                                              'A.type as event_type')
                  ->distinct()
                  ->orderby('E.name','ASC')
                  ->get();

        return $cancelled_classes;

        return View::make('reports.class-status.cancellation-report')->with('cities',$cities)->with('total_classes',$total_classes)->with('cancelled_classes',$cancelled_classes);
    }

    public function CancellationFilter(){
    	$city= Input::get('city');
    	$reason = Input::get('reason');

        $start_date_value = Input::get('start_date');
        $end_date_value = Input::get('end_date');

    	$start_date = date('c',strtotime(Input::get('start_date')));
    	$end_date = date('c',strtotime(Input::get('end_date')));

    	$cities = City::where('id','<=','25')->orderby('name','ASC')->get();
    	//return Input::get('start_date');

    	$tables = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id');

        if($city!='0' && $reason!='0' && !empty($start_date_value) && !empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1111
    	else if($city!='0' && $reason!='0' && !empty($start_date_value) && empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();
    	}//1110
    	else if($city!='0' && $reason!='0' && empty($start_date_value) && !empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1101
    	else if($city!='0' && $reason!='0' && empty($start_date_value) && empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1100
    	else if($city!='0' && $reason=='0' && !empty($start_date_value) && !empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1011
    	else if($city!='0' && $reason=='0' && !empty($start_date_value) && empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1010
    	else if($city!='0' && $reason=='0' && empty($start_date_value) && !empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1001
    	else if($city!='0' && $reason=='0' && empty($start_date_value) && empty($end_date_value)){

            $cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->distinct()->orderby('E.name','ASC')->get();

    		$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->distinct()->where('A.status','<>','created')->get();

            //var_dump($total_classes);
            //return '';


    	}//1000
    	else if($city=='0' && $reason!='0' && !empty($start_date_value) && !empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();


    	}//0111
    	else if($city=='0' && $reason!='0' && !empty($start_date_value) && empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0110
    	else if($city=='0' && $reason!='0' && empty($start_date_value) && !empty($end_date_value)){

        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0101
    	else if($city=='0' && $reason!=0 && empty($start_date_value) && empty($end_date_value)){

    		$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->distinct()->orderby('E.name','ASC')->get();

    		$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0100
    	else if($city=='0' && $reason=='0' && !empty($start_date_value) && !empty($end_date_value)){

        	$cancelled_classes = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0011
    	else if($city=='0' && $reason=='0' && !empty($start_date_value) && empty($end_date_value)){
        	$cancelled_classes = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0010
    	else if($city=='0' && $reason=='0' && empty($start_date_value) && !empty($end_date_value)){
        	$cancelled_classes = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0001
    	else{
    		$cancelled_classes = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->distinct()->orderby('E.name','ASC')->get();

    		$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0000
        //return $cancelled_classes;


        return View::make('reports.class-status.cancellation-report')->with('cities',$cities)->with('total_classes',$total_classes)->with('cancelled_classes',$cancelled_classes)->with('reason',$reason)->with('city_id',$city)->with('start_date',Input::get('start_date'))->with('end_date',Input::get('end_date'));
    }

    public function showChildReport(){
        //echo $cities;
        $child_data = Student::join('propel_student_wingman as B','Student.id','=','B.student_id')->join('User as C','C.id','=','B.wingman_id')->join('Center as D','D.id','=','Student.center_id')->join('City as E','E.id','=','D.city_id')->select('Student.name as name','C.name as wingman_name','D.id as center_id','D.name as center_name','E.name as city_name','E.id as city_id')->where('E.id','<=','25')->where('C.user_type','=','volunteer')->get();

        $total_classes=count($child_data);

        $city_data = Student::join('propel_student_wingman as B','Student.id','=','B.student_id')->join('User as C','C.id','=','B.wingman_id')->join('Center as D','D.id','=','Student.center_id')->join('City as E','E.id','=','D.city_id')->select('E.name as city_name','E.id as city_id',DB::raw('count(Student.id) as Count' ))->distinct()->groupby('E.id')->where('E.id','<=','25')->where('C.user_type','=','volunteer')->where('C.status','=','1')->orderby('E.name','ASC')->get();


        return View::make('reports.child-report.city-data')->with('city_data',$city_data)->with('total_classes',$total_classes);

    }

    public function showCityReport($city_id,$start_date = null, $end_date = null){

        $cities = City::where('id','<=','25')->orderby('name','ASC')->get();
        $centers = Center::where('city_id','=',$city_id)->where('status','=',1)->orderby('name','ASC')->get();

        $tables = DB::table('Student')->join('propel_student_wingman as B','Student.id','=','B.student_id')->join('User as C','C.id','=','B.wingman_id')->join('Center as D','D.id','=','Student.center_id')->join('City as E','E.id','=','D.city_id');

        $child_data = $tables->select('Student.id as id','Student.name as name','C.name as wingman_name','D.id as center_id','D.name as center_name','E.name as city_name','E.id as city_id')->distinct()->where('E.id','=',$city_id)->where('C.user_type','=','volunteer')->where('C.status','=','1')->orderby('D.name','ASC')->get();

        $child_data = (array) $child_data;
        $total = array();

        $total['wingman_session_count'] = 0;
        $total['asv_session_count'] = 0;
        $total['journal_count'] = 0;

        foreach ($child_data as $child){
        	$calendarEvent = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->where('A.student_id','=',$child->id)->get();
        	$child->wingman_session_count = count($calendarEvent);
            $total['wingman_session_count'] += $child->wingman_session_count;

            $calendarEvent = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->where('A.student_id','=',$child->id)->where('A.status','=','attended')->get();
            $child->wingman_module_attended = count($calendarEvent);

        	$calendarEvent = CalendarEvent::where('student_id','=',$child->id)->where('type','=','volunteer_time')->get();
        	$child->asv_session_count = count($calendarEvent);
            $total['asv_session_count'] += $child->asv_session_count;

        	$journalEntry = WingmanJournal::where('student_id','=',$child->id)->where('type','=','child_feedback')->get();
        	$child->journal_count = count($journalEntry);
            $total['journal_count'] += $child->journal_count;
          }


        return View::make('reports.child-report.city-report')->with('child_data',$child_data)->with('cities',$cities)->with('city_id',$city_id)->with('centers',$centers)->with('center_id','0')->with('total',$total);

    }

    public function showCenterReport($city_id,$center_id){

        $cities = City::where('id','<=','25')->orderby('name','ASC')->get();
        $centers = Center::where('city_id','=',$city_id)->where('status','=',1)->orderby('name','ASC')->get();

        $tables = DB::table('Student')->join('propel_student_wingman as B','Student.id','=','B.student_id')->join('User as C','C.id','=','B.wingman_id')->join('Center as D','D.id','=','Student.center_id')->join('City as E','E.id','=','D.city_id');

        $child_data = $tables->select('Student.id as id','Student.name as name','C.name as wingman_name','D.id as center_id','D.name as center_name','E.name as city_name','E.id as city_id')->distinct()->where('E.id','=',$city_id)->orderby('D.name','ASC')->where('D.id','=',$center_id)->get();

        //$child_data = $tables->select('Student.name as name','C.name as wingman_name','D.id as center_id','D.name as center_name','E.name as city_name','E.id as city_id','F.id','F.id as journal_count','G.id as event_count')->distinct()->where('E.id','=',$city_id)->where('F.type','=','child_feedback')->orderby('D.name','ASC')->get(); //Test Query

        $child_data = (array) $child_data;
        $total = array();

        $total['wingman_session_count'] = 0;
        $total['asv_session_count'] = 0;
        $total['journal_count'] = 0;

        foreach ($child_data as $child){
            $calendarEvent = CalendarEvent::where('student_id','=',$child->id)->where('status','<>','cancelled')->where('type','=','wingman_time')->get();
            $child->wingman_session_count = count($calendarEvent);
            $total['wingman_session_count'] += $child->wingman_session_count;

            $calendarEvent = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->where('A.student_id','=',$child->id)->where('A.status','=','attended')->get();
            $child->wingman_module_attended = count($calendarEvent);

            $calendarEvent = CalendarEvent::where('student_id','=',$child->id)->where('status','<>','cancelled')->where('type','=','volunteer_time')->get();
            $child->asv_session_count = count($calendarEvent);
            $total['asv_session_count'] += $child->asv_session_count;

            $journalEntry = WingmanJournal::where('student_id','=',$child->id)->where('type','=','child_feedback')->get();
            $child->journal_count = count($journalEntry);
            $total['journal_count'] += $child->journal_count;
          }


        return View::make('reports.child-report.city-report')->with('child_data',$child_data)->with('cities',$cities)->with('city_id',$city_id)->with('centers',$centers)->with('center_id','0')->with('total',$total);

    }

    public function showCityReportForm(){
        $city_id = Input::get('city');
        $center_id = Input::get('centers');
        //return $city_id;
        if($center_id=="0"){
            return Redirect::to(URL::to('/reports/child-report/').'/'.$city_id);
        }
        else{
            return Redirect::to(URL::to('/reports/child-report/').'/'.$city_id.'/'.$center_id);
        }
    }

    public function ASVcalendarSummary($city_id = null,$start_date = null, $end_date = null) {

        $cities = DB::table('City')->where('id','<',26)->orderBy('name','ASC')->get();

        if($city_id == null){

              $tables = DB::table('propel_calendarEvents as A')->join('propel_volunteerTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.volunteer_id')->join('City as D','D.id','=','C.city_id');

              $query = $tables->select('C.id','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(D.id)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26);

              if(isset($start_date) && $start_date!='null'){
                  $start = date('Y-m-d 00:00:00',strtotime($start_date));
                  $query = $query->where('A.start_time','>=',$start);
              }
              else{
                  $query = $query->where('A.start_time','>=',$this->year_time);
              }

              if(isset($end_date) && $end_date!='null'){
                  $end = date('Y-m-d 00:00:00',strtotime($end_date));
                  $query = $query->where('A.end_time','<=',$end);
              }

              $data_collection = $query->get();

              $datas = array();
              $id = 0;

              foreach ($data_collection as $data) {

                  if($data->id!=$city_id){
                      $id = $data->city_id;

                      $datas[$id]['city_id'] = $data->city_id;
                      $datas[$id]['city_name'] = $data->city_name;
                      if($data->status == 'approved'){
                          $datas[$id]['approved'] = $data->event_count;
                      }
                      if($data->status == 'attended'){
                          $datas[$id]['attended'] = $data->event_count;
                      }

                  }
                  else{
                      if($data->status == 'approved'){
                          $datas[$id]['approved'] = $data->event_count;
                      }
                      if($data->status == 'attended'){
                          $datas[$id]['attended'] = $data->event_count;
                      }

                      $id = $data->city_id;
                  }
              }

              return $data_collection;
              return View::make('reports.attendance.attendance-report')->with('datas',$datas)->with('start_date',$start_date)->with('end_date',$end_date);
          }
          else{

              $tables = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.wingman_id')->join('City as D','D.id','=','C.city_id');

              $query = $tables->select('C.id as wingman_id','C.name as wingman_name','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(C.id)'))->groupby('C.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26)->where('D.id',$city_id)->orderby('D.name','ASC');

              if(isset($start_date) && $start_date!='null'){
                  $start = date('Y-m-d 00:00:00',strtotime($start_date));
                  $query = $query->where('A.start_time','>=',$start);
              }
              else{
                  $query = $query->where('A.start_time','>=',$this->year_time);
              }

              if(isset($end_date) && $end_date!='null'){
                  $end = date('Y-m-d 00:00:00',strtotime($end_date));
                  $query = $query->where('A.end_time','<=',$end);
              }

              $data_collection = $query->get();

              $datas = array();
              $id = 0;

              foreach ($data_collection as $data) {

                  if($data->wingman_id!=$id){
                      $id = $data->wingman_id;

                      $datas[$id]['wingman_name'] = $data->wingman_name   ;
                      $datas[$id]['city_name'] = $data->city_name;
                      if($data->status == 'approved'){
                          $datas[$id]['approved'] = $data->event_count;
                      }
                      if($data->status == 'attended'){
                          $datas[$id]['attended'] = $data->event_count;
                      }

                  }
                  else{
                      if($data->status == 'approved'){
                          $datas[$id]['approved'] = $data->event_count;
                      }
                      if($data->status == 'attended'){
                          $datas[$id]['attended'] = $data->event_count;
                      }

                      $id = $data->wingman_id;
                  }
              }



              return View::make('reports.attendance.city-attendance-report')->with('datas',$datas)->with('cities',$cities)->with('city_id',$city_id)->with('start_date',$start_date)->with('end_date',$end_date);
          }




    }

    public function calendarSummary(){
        return View::make('reports.calendar-summary');
    }

}

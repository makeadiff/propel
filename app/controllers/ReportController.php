<?php

class ReportController extends BaseController
{

    public function showCities()
    {
        $cities = City::all();

        return View::make('reports.class-status.select-city')->with('cities',$cities);
    }

    public function showReports()
    {
        return View::make('reports.select-report');
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

       /* var_dump($report);
        exit;*/

        Excel::create('Wingman-Journal-Report', function($excel) use($report) {

                $excel->sheet('Report', function($sheet) use($report)  {

                        $sheet->fromArray($report);

                    });

            })->export('csv');
    }


    public function attendanceHome(){
        return View::make('reports.attendance.home');
    }

    public function showAttendanceReport($city_id = null,$event_type = null,$start_date = null, $end_date = null) {

        $cities = DB::table('City')->where('id','<',26)->orderBy('name','ASC')->get();

        if(isset($event_type) && $event_type=="wingman_time"){

            if($city_id =='null'){
                
                $tables = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.wingman_id')->join('City as D','D.id','=','C.city_id');

                $query = $tables->select('C.id','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(D.id)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26)->orderby('D.name','ASC');

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

                        $id = $data->id;                
                    }
                }

                return View::make('reports.attendance.attendance-report')->with('datas',$datas)->with('event_type',$event_type);   
            }
            else{

                $tables = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.wingman_id')->join('City as D','D.id','=','C.city_id');

                $query = $tables->select('C.id as wingman_id','C.name as wingman_name','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(C.id)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26)->where('D.id',$city_id)->orderby('D.name','ASC');

                $data_collection = $query->get();

                $datas = array();
                $id = 0;

                foreach ($data_collection as $data) {
                    
                    if($data->wingman_id!=$id){
                        $id = $data->wingman_id;

                        $datas[$id]['wingman_id'] = $data->wingman_id;
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

                        $id = $data->id;                
                    }
                }



                return View::make('reports.attendance.city-attendance-report')->with('datas',$datas)->with('event_type',$event_type)->with('cities',$cities)->with('city_id',$city_id);
            }   
        }
        else if(isset($event_type) && $event_type=="volunteer_time"){
            
            $tables = DB::table('propel_calendarEvents as A')->join('propel_volunteerTimes as B','A.id','=','B.calendar_event_id')->join('User as C','C.id','=','B.volunteer_id')->join('City as D','D.id','=','C.city_id');

            $query = $tables->select('C.id','D.name as city_name','A.status','C.city_id as city_id',DB::raw('count(A.status) as event_count'),DB::raw('count(D.id)'))->groupby('D.id')->groupby('A.status')->where('A.status','<>','cancelled')->where('A.status','<>','created')->where('D.id','<',26)->orderby('D.name','ASC');

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

                    $id = $data->id;                
                }
            }

            return View::make('reports.attendance.attendance-report')->with('datas',$datas)->with('event_type',$event_type);      
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
        $cities = City::where('id','<=','25')->orderby('name','ASC')->get();

        $tables = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id');
        
        $total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

        $cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->distinct()->orderby('E.name','ASC')->get();

        //var_dump($total_classes);
        //return '';

        return View::make('reports.class-status.cancellation-report')->with('cities',$cities)->with('total_classes',$total_classes)->with('cancelled_classes',$cancelled_classes);
    }

    public function CancellationFilter(){
    	$city= Input::get('city');
    	$reason = Input::get('reason');

    	$start_date = date('c',strtotime(Input::get('start_date')));
    	$end_date = date('c',strtotime(Input::get('end_date')));
    	
    	$cities = City::where('id','<=','25')->orderby('name','ASC')->get();
    	//return Input::get('start_date');
    	
    	$tables = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id');

        if($city!='0' && $reason!='0' && !empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1111
    	else if($city!='0' && $reason!='0' && !empty(Input::get('start_date')) && empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();
    	}//1110
    	else if($city!='0' && $reason!='0' && empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();
    	
    	}//1101
    	else if($city!='0' && $reason!='0' && empty(Input::get('start_date')) && empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('B.reason','=',$reason)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();
    	
    	}//1100
    	else if($city!='0' && $reason=='0' && !empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1011
    	else if($city!='0' && $reason=='0' && !empty(Input::get('start_date')) && empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1010
    	else if($city!='0' && $reason=='0' && empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//1001
    	else if($city!='0' && $reason=='0' && empty(Input::get('start_date')) && empty(Input::get('end_date'))){
    		
            $cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->distinct()->orderby('E.name','ASC')->get();

    		$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('E.id','=',$city)->distinct()->where('A.status','<>','created')->get();

            //var_dump($total_classes);
            //return '';


    	}//1000
    	else if($city=='0' && $reason!='0' && !empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();


    	}//0111
    	else if($city=='0' && $reason!='0' && !empty(Input::get('start_date')) && empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0110
    	else if($city=='0' && $reason!='0' && empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
        	
        	$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0101
    	else if($city=='0' && $reason!=0 && empty(Input::get('start_date')) && empty(Input::get('end_date'))){
    		
    		$cancelled_classes = $tables->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('B.reason','=',$reason)->distinct()->orderby('E.name','ASC')->get();

    		$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0100
    	else if($city=='0' && $reason=='0' && !empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
        	
        	$cancelled_classes = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->where('A.start_time','<=',$end_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0011
    	else if($city=='0' && $reason=='0' && !empty(Input::get('start_date')) && empty(Input::get('end_date'))){
        	$cancelled_classes = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->get();

        	$total_classes = DB::table('propel_calendarEvents as A')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name','A.type as event_type')->where('A.start_time','>=',$start_date)->distinct()->orderby('E.name','ASC')->where('A.status','<>','created')->get();

    	}//0010
    	else if($city=='0' && $reason=='0' && empty(Input::get('start_date')) && !empty(Input::get('end_date'))){
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
        $child_data = Student::join('propel_student_wingman as B','Student.id','=','B.student_id')->join('User as C','C.id','=','B.wingman_id')->join('Center as D','D.id','=','Student.center_id')->join('City as E','E.id','=','D.city_id')->select('Student.name as name','C.name as wingman_name','D.id as center_id','D.name as center_name','E.name as city_name','E.id as city_id')->where('E.id','<=','25')->get();

        $total_classes=count($child_data);

        $city_data = Student::join('propel_student_wingman as B','Student.id','=','B.student_id')->join('User as C','C.id','=','B.wingman_id')->join('Center as D','D.id','=','Student.center_id')->join('City as E','E.id','=','D.city_id')->select('E.name as city_name','E.id as city_id',DB::raw('count(B.id) as Count' ))->groupby('E.id')->where('E.id','<=','25')->orderby('E.name','ASC')->get();

        $city_data;
        
        //echo '<b>Total Kids in Propel: '.count($child_data).'</b><br/><br/>';

        /*foreach ($child_data as $child){
            echo $child->name.' - '.$child->wingman_name.' | '.$child->center_name.' | '.$child->city_name.'<br/>';
        }*/

        return View::make('reports.child-report.city-data')->with('city_data',$city_data)->with('total_classes',$total_classes);

    }

    public function showCityReport($city_id){

        $cities = City::where('id','<=','25')->orderby('name','ASC')->get();
        $centers = Center::where('city_id','=',$city_id)->where('status','=',1)->orderby('name','ASC')->get();        

        $tables = DB::table('Student')->join('propel_student_wingman as B','Student.id','=','B.student_id')->join('User as C','C.id','=','B.wingman_id')->join('Center as D','D.id','=','Student.center_id')->join('City as E','E.id','=','D.city_id');

        $child_data = $tables->select('Student.id as id','Student.name as name','C.name as wingman_name','D.id as center_id','D.name as center_name','E.name as city_name','E.id as city_id')->distinct()->where('E.id','=',$city_id)->orderby('D.name','ASC')->get();

        $child_data = (array) $child_data;
        $total = array();
        
        $total['wingman_session_count'] = 0;
        $total['asv_session_count'] = 0;
        $total['journal_count'] = 0;

        foreach ($child_data as $child){
        	$calendarEvent = DB::table('propel_calendarEvents as A')->join('propel_wingmanTimes as B','A.id','=','B.calendar_event_id')->where('A.student_id','=',$child->id)->get();
        	$child->wingman_session_count = count($calendarEvent);
            $total['wingman_session_count'] += $child->wingman_session_count;
        	
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

    public function calendarSummary(){
        return View::make('reports.calendar-summary');
    }

}

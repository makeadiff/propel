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

    public function showAttendanceReport() {
        $report = DB::select("SELECT DATE(ce.start_time) as Date, ce.type as Type, s.name as Student, IFNULL(w.name,'N/A') as Wingman,IFNULL(wm.name,'N/A') as WingmanModule, IFNULL(v.name,'N/A') as Volunteer, c.name as Center, cy.name as City, ce.status as Status, IFNULL(cce.reason,'N/A') as CancelReason FROM `propel_calendarEvents` as ce
                                INNER JOIN Student as s
                                ON s.id = ce.student_id
                                INNER JOIN Center as c
                                ON c.id = s.center_id
                                INNER JOIN City as cy
                                ON cy.id = c.city_id
                                LEFT OUTER JOIN propel_wingmanTimes as wt
                                ON wt.calendar_event_id = ce.id
                                LEFT OUTER JOIN User as w
                                ON wt.wingman_id = w.id
                                LEFT OUTER JOIN propel_wingmanModules as wm
                                ON wt.wingman_module_id = wm.id
                                LEFT OUTER JOIN propel_volunteerTimes as vt
                                ON vt.calendar_event_id = ce.id
                                LEFT OUTER JOIN User as v
                                ON vt.volunteer_id = v.id
                                LEFT OUTER JOIN propel_cancelledCalendarEvents as cce
                                ON cce.calendar_event_id = ce.id");

//        $report = json_decode(json_encode($report),true);

        return $report;

        Excel::create('Attendance-Report', function($excel) use($report) {

                $excel->sheet('Report', function($sheet) use($report)  {

                        $sheet->fromArray($report);

                    });

            })->export('csv');
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

        $classes = CancelledCalendarEvent::all();
        $total_classes = CalendarEvent::where('status','=','approved')->get();

        $cancelled_classes = DB::table('propel_calendarEvents as A')->join('propel_cancelledCalendarEvents as B','B.calendar_event_id','=','A.id')->join('Student as C','C.id','=','A.student_id')->join('propel_student_wingman as F','F.student_id','=','C.id')->join('User as G','G.id','=','F.wingman_id')->join('Center as D','D.id','=','C.center_id')->join('City as E','E.id','=','D.city_id')->select('A.id as event_id','A.type as event_type','B.comment as comment','B.reason as reason','B.updated_at as cancelled_time','C.name as student_name','C.id as student_id','D.name as center_name','D.id as center_id','E.name as city_name','E.id as city_id','A.start_time as start_time','A.end_time as end_time','G.name as wingman_name')->distinct()->orderby('E.name','ASC')->get();

        //return $cancelled_classes;

        return View::make('reports.class-status.cancellation-report')->with('cities',$cities)->with('classes',$classes)->with('total_classes',$total_classes)->with('cancelled_classes',$cancelled_classes);
    }


    public function showCalendarReport(){
        
    }

}

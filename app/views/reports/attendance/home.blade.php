@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Attendance Reports</h2>
        <br>
        <div class="row">

                <div class="col-md-4 col-sm-12 text-center">
                     <a href="{{URL::to('reports/attendance-report/null/wingman_time')}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/attendance.png')}}"><br>Wingman Session <br/> Attendance</a>
                </div>

                <div class="col-md-4 col-sm-12 text-center">
                     <a href="{{URL::to('reports/attendance-report/null/volunteer_time')}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/attendance.png')}}"><br>ASV Session <br/> Attendance</a>
                </div>

                
                <!--<a class="btn btn-primary" href="reports/wingman-journal-report">Wingman Journal</a><br><br>
                <a class="btn btn-primary" href="reports/attendance-report">Attendance</a><br>
                -->
            <br>

            <br>
        </div>
    </div>
</div>


@stop

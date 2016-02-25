@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Reports</h2>
        <br>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">


                <div class="col-md-4 col-sm-6 text-center">
                     <a href='reports/attendance-report' class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/attendance.png')}}"><br>Attendance</a>
                </div>

                <div class="col-md-4 col-sm-6 text-center">
                     <a href="{{{URL::to('reports/class-cancelled-report')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/reports.png')}}"><br>Cancelled<br/>Classes</a>
                </div>

                <div class="col-md-4 col-sm-6 text-center">
                     <a href="{{{URL::to('reports/child-report')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/kids.png')}}"><br><br>Child Data</a>
                </div>

                <!--<a class="btn btn-primary" href="reports/wingman-journal-report">Wingman Journal</a><br><br>
                <a class="btn btn-primary" href="reports/attendance-report">Attendance</a><br>
                -->

            </div>
            <br>

            <br>
        </div>
    </div>
</div>


@stop

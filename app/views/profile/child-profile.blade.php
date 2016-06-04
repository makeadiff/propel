@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h3 class="sub-title">Child Profile - {{$student->name}}</h3>
        <br>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">


                <div class="col-md-4 col-sm-6 text-center">
                     <a href='../calendar/{{$wingman->id}}/{{$student->id}}' class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/calendar.png')}}"><br>Child Calendar</a>
                </div>

                <div class="col-md-4 col-sm-6 text-center">
                     <a href='../feedback/{{$wingman->id}}/{{$student->id}}' class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/reports.png')}}"><br>Child Feedback</a>
                </div>

                <div class="col-md-4 col-sm-6 text-center">
                     <a href='../feedback/{{$wingman->id}}/{{$student->id}}' class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/attendance.png')}}"><br>Wingman Modules</a>
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

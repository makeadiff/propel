@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="centered">
        <div class="row">

            <h2 class="sub-title">Wingman/ASV Attendance</h2>
            <br>

            <div style="padding:10px" class="col-md-3-offset-3 col-sm-6 text-center">
                <a href="{{URL::to('/attendance/wingman/')}}" class='btn btn-primary btn-dash transparent'><img  src="{{URL::to('/img/attendance.png')}}"><br/>Wingman Attendance</a>
            </div>

            <div style="padding:10px" class="col-md-3-offset-2 col-sm-6 text-center">
                <a href="{{URL::to('/attendance/asv/')}}" class='btn btn-primary btn-dash transparent'><img  src="{{URL::to('/img/attendance.png')}}"><br/>ASV Attendance</a>
            </div>

        </div>

    </div>

</div>




@stop

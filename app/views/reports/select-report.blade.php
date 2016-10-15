@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Reports</h2>
        <br>

        <!--<div class="row">
            <div class="col-md-3 col-md-offset-1 text-center">
                <h1>{{$wingmen_adoption}}%</h1>
                Wingman Adoption
            </div>
            <div class="col-md-4 text-center">
              <div class="row">
                  <div class="col-md-6 text-center">

                  </div>
                  <div class="col-md-6 text-center">
                  </div>
              </div>
            </div>
            <div class="col-md-3 text-center">
                 Fellow Adoption
            </div>
            <div class="col-md-1"></div>
        </div>-->
        <div class="row">
            <div class="col-md-3 col-sm-6 text-center">
                 <a href='reports/attendance' class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/attendance.png')}}"><br>Attendance</a>
            </div>

            <div class="col-md-3 col-sm-6 text-center">
                 <a href="{{{URL::to('reports/calendar-summary')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/calendar.png')}}"><br><br>Calendar <br/>Summary</a>
            </div>

            <div class="col-md-3 col-sm-6 text-center">
                 <a href="{{{URL::to('reports/class-cancelled-report')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/reports.png')}}"><br>Cancelled<br/>Classes</a>
            </div>

            <div class="col-md-3 col-sm-6 text-center">
                 <a href="{{{URL::to('reports/child-report')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/kids.png')}}"><br><br>Child Data</a>
            </div>
            <br><br>
        </div>
    </div>
</div>


@stop

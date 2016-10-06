@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Reports</h2>
        <br>
        <div class="row">
                <div class="col-md-2 col-sm-12 text-center"></div>
                <div class="col-md-4 col-sm-12 text-center">
                     <a href="{{{URL::to('reports/calendar-approval')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/calendar.png')}}"><br>Calendar Approval <br/> Summary</a>
                </div>

                <!--<div class="col-md-4 col-sm-12 text-center">
                     <a href="{{{URL::to('reports/asv-calendar-summary')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/calendar.png')}}"><br> ASV Calendar <br/>Summary</a>
                </div>-->
                <div class="col-md-2 col-sm-12 text-center"></div>
            <br>

            <br>
        </div>
    </div>
</div>


@stop

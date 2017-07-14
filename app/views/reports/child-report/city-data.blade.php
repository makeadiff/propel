@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.date.css" id="theme_date">
@stop

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Select City</h2>
        <br>

        <div class="row center">
        	<button class="btn btn-primary" type="button">
        	<!-- <a href="{{URL::to('/report/child-report/national')}}"> -->
			  National <span class="badge">{{$total_classes}}</span>
			</button>
        </div>


        <div class="row">


                @foreach($city_data as $city)
                    <div style="padding:10px" class="col-md-3 col-sm-6">
                        <a href="{{URL::to('/reports/child-report/'.$city->city_id)}}" class="btn btn-primary btn-dash transparent"><img  src="{{URL::to('/img/cities.png')}}"><br/></a><br/>
                        <button class="btn btn-primary" type="button">
                        <a href="{{URL::to('/reports/child-report/'.$city->city_id)}}">
						  {{$city->city_name}} <span class="badge">{{$city->Count}}</span>
						</a>
						</button>
                        <!--<span class="label label-success" style="font-size:13px">Propellers: {{$city->Count}}</span>-->
                    </div>
                @endforeach

        </div>
        <br/><br/>
    </div>
</div>

@stop

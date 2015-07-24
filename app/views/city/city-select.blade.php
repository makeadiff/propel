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


        <div class="row">
                @foreach($cities as $city)
                    <div style="padding:10px" class="col-md-4 col-sm-6">
                        <a href="{{URL::to('/city-change/city/'. $city->id)}}" class="btn btn-primary btn-dash transparent"><img  src="{{URL::to('/img/cities.png')}}"><br/>{{$city->name}}</a><br>
                    </div>
                @endforeach
        </div>
    </div>
</div>

@stop

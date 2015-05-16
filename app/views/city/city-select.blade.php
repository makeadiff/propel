@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
@stop

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Select City</h2>
        <br>


        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                @foreach($cities as $city)
                    <a href="{{{URL::to('/city-change/city/'. $city->id)}}}" class="white">{{{$city->name}}}</a><br>
                @endforeach
            </div>
        </div>
    </div>
</div>

@stop

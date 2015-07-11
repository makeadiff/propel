@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
@stop

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Select Multiplier</h2>
        <br>


        <div class="row">
                @foreach($fellows as $fellow)
                    <div style="padding:10px" class="col-md-4 col-sm-6">
                        <a href="{{{URL::to('/city-change/fellow/' . $fellow->id)}}}" class="btn btn-primary btn-dash transparent"><img  src="{{{URL::to('/img/profile.png')}}}"><br/>{{{$fellow->name}}}</a><br>
                    </div>
                @endforeach
        </div>
    </div>
</div>

@stop

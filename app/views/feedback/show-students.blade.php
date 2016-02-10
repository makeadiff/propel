@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.date.css" id="theme_date">
@stop

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Select Student</h2>
        <br>


        <div class="row">
            @foreach($students as $student)
                <div style="padding:10px" class="col-md-4 col-sm-6 text-center">
                    <a href="{{URL::to('/feedback/'. $wingman_id . '/' . $student->id)}}" class='btn btn-primary btn-dash transparent'><img  src="{{URL::to('/img/kids.png')}}"><br/>{{$student->name}}</a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@stop

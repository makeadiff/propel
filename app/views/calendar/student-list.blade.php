@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
@stop

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Select Student</h2>
        <br>


        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                @foreach($students as $student)
                    <a class="btn btn-default" href="{{{URL::to('/calendar/'. $wingman_id . '/' . $student->id)}}}">{{{$student->name}}}</a><br><br>
                @endforeach
            </div>
        </div>
    </div>
</div>

@stop

@extends('layouts.master')

@section('body')
@section('navbar-header')
<a class="navbar-brand" href=".">MAD 360</a>
@stop

@section('navbar-links')
<li><a href="./review">Review</a></li>
<li><a href="./report">Report</a></li>

@stop


<div class="container-fluid">
    <div class="centered board">
        <br>
        <br>
        <h1 class="title">Success!</h1>
        <br>
        <div class="row">
            <p class="success">{{{Session::get('message')}}}</p>
        </div>
        <br>
        <div class="row">
            <a href={{{URL::to('/review')}}} class='btn btn-primary btn-lg transparent'>Back to Review</a>
        </div>
    </div>
</div>
@stop

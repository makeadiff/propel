@extends('layouts.master')

@section('body')


<div class="container-fluid">
    <div class="centered">
        <br>
        <br>
        <h1 class="title">Oops!</h1>
        <br>
        <div class="row">
            <p class="success">
                {{ Session::get('message')}}
            </p>
        </div>
        <br>
        <div class="row">
            <a href='.' class='btn btn-primary btn-lg transparent'>Back to Home</a>
        </div>
    </div>
</div>
@stop

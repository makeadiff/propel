@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>
        <br>
        <h1 class="title">Propel</h1>
        <br>


        <div class="row">
            <a href='wingman-journal/{{{$user_id}}}' class='btn btn-primary btn-lg transparent'>Wingman Journal</a>
        </div>
        <br>

        <br>
    </div>
</div>
@stop

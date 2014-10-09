@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Select City</h2>
        <br>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">

                @foreach($cities as $city)
                    <a href="">{{{$city->name}}}</a>
                @endforeach

            </div>
            <br>

            <br>
        </div>
    </div>
</div>

@stop

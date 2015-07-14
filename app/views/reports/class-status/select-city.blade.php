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
                    <a class="white" href="{{URL::to('/')}}/reports/class-status/city/{{$city->id}}">{{$city->name}}</a><br>
                @endforeach

            </div>
            <br>

            <br>
        </div>
    </div>
</div>

@stop

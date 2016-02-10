@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="centered">
        <div class="row">

            <h2 class="sub-title">Select Center</h2>
            <br>


            @foreach($centers as $center)
                <div style="padding:10px" class="col-md-4 col-sm-6 text-center">
                    <a href="{{URL::to('/calendar/center/'. $center->id)}}" class='btn btn-primary btn-dash transparent'><img  src="{{URL::to('/img/centers.png')}}"><br/>{{$center->name}}</a>
                </div>
            @endforeach


        </div>

    </div>

</div>




@stop

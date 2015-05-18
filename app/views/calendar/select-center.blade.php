@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="row">
        <div class="col-md-6 col-md-offset-3 white">

            <h2 class="sub-title">Select Center</h2>
            <br>


            <div class="row">
                <div class="col-md-offset-2 col-md-8 text-center">
                    @foreach($centers as $center)
                    <a class="btn btn-default" href="{{{URL::to('/calendar/center/'. $center->id)}}}">{{{$center->name}}}</a><br><br>
                    @endforeach
                </div>
            </div>


        </div>

    </div>

</div>




@stop

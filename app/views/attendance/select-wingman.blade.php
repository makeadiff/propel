@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="row">
        <div class="col-md-6 col-md-offset-3 white">

            <h2 class="sub-title">Select Wingman</h2>
            <br>


            <div class="row">
                <div class="col-md-offset-2 col-md-8 text-center">
                    @foreach($wingmen as $wingman)
                        <a class="btn btn-primary" href="{{{URL::to('/attendance/'. $wingman->id)}}}">{{{$wingman->name}}}</a><br><br>
                    @endforeach
                </div>
            </div>


        </div>

    </div>

</div>




@stop

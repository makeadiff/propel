@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="centered">
        <div class="row">

            <h2 class="sub-title">Select Wingman</h2>
            <br>


                @foreach($wingmen as $wingman)
                    <div style="padding:10px" class="col-md-4 col-sm-6 text-center">
                        <a href="{{{URL::to('/attendance/'. $wingman->id)}}}" class='btn btn-primary btn-dash transparent'><img  src="{{{URL::to('/img/profile.png')}}}"><br/>{{{$wingman->name}}}</a>
                    </div>
                @endforeach


        </div>

    </div>

</div>




@stop

@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="centered">
        <div class="row">

            <h2 class="sub-title">Select Wingman</h2>
            <br>

            @if(count($wingmen)!=0)
                @foreach($wingmen as $wingman)
                    <div style="padding:10px" class="col-md-3 col-sm-6 col-xs-12 text-center">
                        <a href="{{URL::to('/attendance/wingman/'. $wingman->id)}}" class='btn btn-primary btn-dash transparent'><img  src="{{URL::to('/img/profile.png')}}"><br/>{{$wingman->name}}</a>
                    </div>
                @endforeach
            @else
                <p style="text-align:center; color:#FFF">Wingmen aren't assigned to fellows.<br/>
                    Go to Assign Wingman on the home screen of MADApp for Propel.
                </p><br/><br/>
                <div class="centered">
                    <a class="btn btn-default" href="{{URL::to('/')}}">Go Back</a>
                </div>
            @endif


        </div>

    </div>

</div>




@stop

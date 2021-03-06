@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="centered">
        <div class="row">

            <h2 class="sub-title">Select ASV</h2>
            <br>


                @foreach($asvs as $asv)
                    <div style="padding:10px" class="col-md-3 col-sm-12 text-center">
                        <a href="{{{URL::to('/calendar/asv/'. $asv->id)}}}" class='btn btn-primary btn-dash transparent'><img  src="{{{URL::to('/img/profile.png')}}}"><br/>{{{ucwords(strtolower($asv->name))}}}</a>
                    </div>
                @endforeach
        
        </div>

    </div>

</div>




@stop

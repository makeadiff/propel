@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
@stop


@section('body')

<div class="container-fluid">


    <div class="row">
        <div class="col-md-6 col-md-offset-3 white">

            <h2 class = "sub-title centered">{{{$journal_entry->title}}}</h2><br>
            <h4 class="sub-title">Type : </h4>&nbsp;{{{$journal_entry->type}}}<br>


            <h4 class="sub-title">Student : </h4>&nbsp;{{{$journal_entry->student()->first()->name}}}<br>

            <h4 class="sub-title">Date : </h4>&nbsp;{{{$journal_entry->on_date}}}<br><br>

            <h4 class="sub-title">Minutes of Meeting :</h4><br>

            {{{$journal_entry->mom}}}

            <div class="centered">
                <a href="{{{URL::to('')}}}" class="btn btn-primary text-center">Back</a>
            </div>

        </div>

    </div>

</div>

<script>
    $(document).ready(function(){
        $('#pickdate').pickadate();
    });
</script>
<script src="{{{URL::to('/')}}}/js/picker.js"></script>
<script src="{{{URL::to('/')}}}/js/picker.date.js"></script>


@stop

@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.date.css" id="theme_date">
@stop


@section('body')

<div class="container-fluid">


    <div class="row">
        <div class="col-md-6 col-md-offset-3 white">

            <h2 class = "sub-title centered">{{$journal_entry->title}}</h2><br>
            <h4 class="sub-title">Type: 
            <?php
                if($journal_entry->type=="child_feedback"){
                    echo "Child Feedback";
                }
                else if($journal_entry->type=="module_feedback"){
                    echo "Module Feedback (".$journal_entry->title.")";
                }
                else{
                    echo "Other";
                }
            ?>
            </h4><br/>


            <h4 class="sub-title">Student: {{$journal_entry->student()->first()->name}}</h4><br>

            <h4 class="sub-title">Date: {{$journal_entry->on_date}}</h4><br><br>

            <h4 class="sub-title">Minutes of Meeting</h4><br>

            <span class="data">{{$journal_entry->mom}}</span>

            <div class="centered">
                <a href="{{URL::to('/wingman-journal/'.$journal_entry->wingman_id)}}" class="btn btn-primary text-center">Back</a>
            </div>

        </div>

    </div>

</div>

<script>
    $(document).ready(function(){
        $('#pickdate').pickadate();
    });
</script>
<script src="{{URL::to('/')}}/js/picker.js"></script>
<script src="{{URL::to('/')}}/js/picker.date.js"></script>


@stop

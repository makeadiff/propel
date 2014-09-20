@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/calendar.css" id="theme_date">
@stop

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Calendar</h2>
        <br>


        <div class="row">
            <div class="col-md-12">
                <?php

                $cal->display();

                function daily($year, $month, $day, $weekday) {

                echo "<div class='text-center'><a data-toggle='modal' data-target='#myModal' class='btn btn-primary btn-sm' style='display:inline-block'>Create/Edit</a>&nbsp;&nbsp;";
                echo "<a href='' class='btn btn-default btn-sm'>Cancel</a></div>";
                }

                ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create/Edit</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <select class="form-control" id="type" name="type">
                        <option>Child Busy</option>
                        <option>Volunteer Time</option>
                        <option>Wingman Time</option>
                    </select>

                    <select class="form-control" id="volunteer" name="volunteer">
                        @foreach($volunteers as $volunteer)
                            <option>{{{$volunteer->name}}}</option>
                        @endforeach
                    </select>

                    <select class="form-control" id="subject" name="subject">
                        @foreach($subjects as $subject)
                            <option>{{{$subject->name}}}</option>
                        @endforeach
                    </select>

                    <select class="form-control" id="wingman_module" name="wingman_module">
                        @foreach($wingman_modules as $wingman_module)
                            <option>{{{$wingman_module->name}}}</option>
                        @endforeach
                    </select>

                    <div class="form-group">
                        <input type="text" id='pickdate' name="pickdate" class="form-control" style="width: 25%" placeholder="Date">
                    </div>

                    <div class="form-group" >
                        <input type="text" class="form-control" name="title" id="title" value="" placeholder="Title">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop

@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.time.css" id="theme_date">
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

                    $date = new DateTime("$day-$month-$year");

                    $date_string = $date->format('Y-m-d');


                    $event = DB::select('SELECT propel_calendarEvents.id FROM propel_calendarEvents
                                            WHERE DATE(propel_calendarEvents.start_time) = ?
                                            AND propel_calendarEvents.student_id = ?
                                            ',array($date,$GLOBALS['student_id']));

                    if(!empty($event[0])){

                        $calendar_event = CalendarEvent::find($event[0]->id);
                        if($calendar_event->type == 'wingman_time'){

                            $wingman = $calendar_event->wingmanTime()->first()->wingman()->first();
                            $wingman_module = $calendar_event->wingmanTime()->first()->wingmanModule()->first();
                            echo "<a class=\"list_popover white\" data-container=\"body\" data-toggle=\"popover\" data-placement=\"top\"
                                 data-content=\"<strong>Wingman :</strong> $wingman->name <br>
                                                <strong>Wingman Module :</strong> $wingman_module->name<br>
                                                <strong>Start Time : </strong>$calendar_event->start_time<br>
                                                <strong>End Time : </strong>$calendar_event->end_time
                                                \">Wingman Time";
                            if(!empty($calendar_event->cancelledCalendarEvent()->first()))
                                echo "(Cancelled)</a>";
                            else
                                echo "</a>";
                            echo "<div class='text-center'><a  data-date=\"$date_string\" class='btn btn-primary btn-sm trigger_create_edit' style='display:inline-block'>Create/Edit</a>&nbsp;&nbsp;";
                            echo "<a data-date=\"$date_string\" class='btn btn-default btn-sm trigger_cancel'>Cancel</a></div>";
                        }elseif($calendar_event->type == 'volunteer_time'){
                            $volunteer = $calendar_event->volunteerTime()->first()->volunteer()->first();
                            $subject = $calendar_event->volunteerTime()->first()->subject()->first();
                            echo "<a class=\"list_popover white\" data-container=\"body\" data-toggle=\"popover\" data-placement=\"top\"
                                 data-content=\"<strong>Volunteer :</strong> $volunteer->name <br>
                                                <strong>Subject :</strong> $subject->name<br>
                                                <strong>Start Time : </strong>$calendar_event->start_time<br>
                                                <strong>End Time : </strong>$calendar_event->end_time
                                                \">Volunteer Time";

                            if(!empty($calendar_event->cancelledCalendarEvent()->first()))
                                echo "(Cancelled)</a>";
                            else
                                echo "</a>";

                            echo "<div class='text-center'><a  data-date=\"$date_string\" class='btn btn-primary btn-sm trigger_create_edit' style='display:inline-block'>Create/Edit</a>&nbsp;&nbsp;";
                            echo "<a data-date=\"$date_string\" class='btn btn-default btn-sm trigger_cancel'>Cancel</a></div>";
                        }elseif($calendar_event->type == 'child_busy'){
                            echo "<span class='white'>Child Busy</span>";echo "<div class='text-center'><a  data-date=\"$date_string\" class='btn btn-primary btn-sm trigger_create_edit' style='display:inline-block'>Create/Edit</a>&nbsp;&nbsp;";

                        }


                    }
                    else{
                        echo "<span class='grey text-center'>Not Marked</span>";
                        echo "<div class='text-center'><a  data-date=\"$date_string\" class='btn btn-primary btn-sm trigger_create_edit' style='display:inline-block'>Create/Edit</a>&nbsp;&nbsp;";

                    }

                }

                ?>
            </div
>            <form action="{{URL::to('/calendar/approve')}}" method="post">
            <input type="hidden" name="student_id" value="{{{$student_id}}}" />
            <input type="hidden" name="month" value="{{{ (isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y')) . "-" . (isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'))}}}" />
            <input type="submit" name="action" value="Approve All" class="btn btn-md btn-success" />
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="createEditModal" tabindex="-1" role="dialog" aria-labelledby="createEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create/Edit</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="{{{URL::to('/calendar/createEdit')}}}">
                    <div class="form-group">
                        <label for="type" class="control-label">Type : </label>
                        <select class="form-control" id="type" name="type">
                            <option value=""></option>
                            <option value="child_busy">Child Busy</option>
                            <option value="volunteer_time">Volunteer Time</option>
                            <option value="wingman_time">Wingman Time</option>
                        </select>
                    </div>


                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="volunteer" class="control-label">Volunteer : </label>
                        <select class="form-control" id="volunteer" name="volunteer">
                            @foreach($volunteers as $volunteer)
                                <option value="{{{$volunteer->id}}}">{{{$volunteer->name}}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="subject" class="control-label">Subject : </label>
                        <select class="form-control" id="subject" name="subject">
                            @foreach($subjects as $subject)
                                <option value="{{{$subject->id}}}">{{{$subject->name}}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group optional wingman-time" style="display:none">
                        <label for="wingman_module" class="control-label">Wingman Module : </label>
                        <select class="form-control" id="wingman_module" name="wingman_module">
                            @foreach($wingman_modules as $wingman_module)
                                <option value="{{{$wingman_module->id}}}">{{{$wingman_module->name}}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_time" class="control-label">Start Time : </label>
                        <div class="form-group">
                            <input type="text" id='start_time' name="start_time" class="form-control" style="width: 25%" placeholder="Start Time">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="control-label">End Time : </label>
                        <div class="form-group">
                            <input type="text" id='end_time' name="end_time" class="form-control" style="width: 25%" placeholder="End Time">
                        </div>
                    </div>

                    <input type="hidden" id="on_date" name="on_date">
                    <input type="hidden" name="student_id" value="{{{$student_id}}}">
                    <input type="hidden" name="wingman_id" value="{{{$wingman_id}}}">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Cancel Class</h4>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{{URL::to('/calendar/cancelEvent')}}}">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="type" class="control-label">Reason : </label>
                        <select class="form-control" id="type" name="reason">
                            <option value="student_not_available">Student Not Available</option>
                            <option value="volunteer_not_available">Volunteer Not Available</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="comment" class="control-label">Comment : </label>
                        <textarea class="form-control" id="comment" name="comment"></textarea>
                    </div>

                    <input type="hidden" id="cancel_on_date" name="cancel_on_date">
                    <input type="hidden" name="student_id" value="{{{$student_id}}}">
                    <input type="hidden" name="wingman_id" value="{{{$wingman_id}}}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    $(document).ready(function(){
        $('#start_time').pickatime();
        $('#end_time').pickatime();
        $('.list_popover').popover({'html' : true});

    });
</script>

<script src="{{{URL::to('/')}}}/js/picker.js"></script>
<script src="{{{URL::to('/')}}}/js/picker.date.js"></script>
<script src="{{{URL::to('/')}}}/js/picker.time.js"></script>

<script>
    $(function(){
        $(".trigger_create_edit").click(function(){
            $("#on_date").val($(this).attr("data-date"));
            $("#createEditModal").modal('show');
        })

        $(".trigger_cancel").click(function(){
            $("#cancel_on_date").val($(this).attr("data-date"));
            $("#cancelModal").modal('show');
        })


        $("#type").change(function () {
            // hide all optional elements
            $('.optional').css('display','none');

            $("#type option:selected").each(function () {
                if($(this).val() == "volunteer_time") {
                    $('.volunteer-time').css('display','block');
                } else if($(this).val() == "wingman_time") {
                    $('.wingman-time').css('display','block');
                }
            });
        });
    })
</script>


@stop

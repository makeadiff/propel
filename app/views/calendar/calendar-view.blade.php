@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.date.css" id="theme_date">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.time.css" id="theme_date">
<link rel="stylesheet" href="{{URL::to('/')}}/css/calendar.css" id="theme_date">
<link href='{{URL::to("/")}}/css/fullcalendar.css' rel='stylesheet' />
<link href='{{URL::to("/")}}/css/fullcalendar.print.css' rel='stylesheet' media='print' />

<style>

    #calendar {
        max-width: 900px;
        margin: 0 auto;
        margin-bottom:50px;
    }

    /*.hiddenEvent{display: none;}
    .fc-other-month .fc-day-number { display:none;}

    td.fc-other-month .fc-day-number {
         visibility: hidden;
    }*/


</style>
<script type="text/javascript">
    volunteer_id;
    module_id;
    subject_id;
    start_time;
    end_time;
    start_date;
    end_date;
    event_id;
    today;
    today_date;
    monthstatus;
    user_group;
</script>
<script src='{{URL::to("/")}}/js/lib/moment.min.js'></script>
<script src='{{URL::to("/")}}/js/fullcalendar.js'></script>
<script type="text/javascript">

    $(document).ready(function() {

            
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                <?php
                    if(isset($_GET['date'])){
                        echo 'defaultDate: \''.$_GET['date'].'\',';
                    }
                ?>
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var title=' ';
                    monthstatus = true;
                    var start_timestamp = new Date(start);
                    var end_timestamp = new Date(end);
                    
                    //End day returned by function is one day ahead, hence subtracting one day
                    end_timestamp.setDate(end_timestamp.getDate()-1);
                    var cur_date = $.datepicker.formatDate('yy-mm-dd',start_timestamp);
                    var end_date = $.datepicker.formatDate('yy-mm-dd',end_timestamp);
                    $("#on_date").val(cur_date);
                    $("#end_date").val(end_date);
                    var start_time = timeFormat(start_timestamp);
                    var end_time = timeFormat(end_timestamp);
                    var unapproved = 0;
                    var events = document.getElementsByClassName('fc-event');
                    var length = events.length;
                    //alert(length);

                    for(var i=0; i<length; i++){
                        if(events.item(i).getAttribute('status')=='created'){
                            monthstatus = false;
                        }
                        else if(events.item(i).getAttribute('status')=='approved' || events.item(i).getAttribute('status')=='cancelled'){
                            unapproved++;
                        }
                    }

                    //alert(unapproved);

                    if((start_timestamp < today_date)){
                        $('#errorCalendar').html('Error loading <strong>Time Machine</strong>: Cannot create events in past!');
                        $('#errorCalendar').fadeIn('slow');
                        $('html,body').animate({ scrollTop: 0 },1000);
                    }
                    else if(monthstatus && user_group=='Propel Wingman' && unapproved!=0){
                        $('#errorCalendar').html('<strong>Error</strong>: Month is already approved');
                        $('#errorCalendar').fadeIn('slow');
                        $('html,body').animate({ scrollTop: 0 },1000);
                    }
                    else{
                        $('#errorCalendar').fadeOut('fast');
                        $("#createModal").modal('show');
                    }
                    $('#start_time').val(start_time);
                    $('#end_time').val(end_time);
                    var time = end_date + ' ' + end_time;
                    var timestamp = Date.parse(time)/1000;
                    //alert(timestamp);
                    var eventData;
                    if (title) {
                        eventData = {
                            id:id,
                            title: title,
                            start: start,
                            end: timestamp
                        };
                        $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                    }
                    $('#calendar').fullCalendar('unselect');
                },
                editable: false,
                eventLimit: false, // allow "more" link when too many events
                
                events: <?php echo $calendarEvents ?>,

                eventClick: function(calEvent, jsEvent, view) {
                    var id = this.id;
                    var data = document.getElementById(id.toString());
                    $('#calendar_event_id').val(id);
                    var string = '<strong>'+ data.name + '</strong>'
                                    + '<br/>' + data.getAttribute('start')
                                    + ' - ' + data.getAttribute('end')
                                    + (data.getAttribute('volunteer_name')?'<br/>Volunteer Name: <strong>'+data.getAttribute('volunteer_name')+'</strong><br/>Subject Name: <strong>'+data.getAttribute('subject_name')+'</strong>':'')
                                    + (data.getAttribute('wingman_name')?'<br/>Wingman Name: <strong>'+data.getAttribute('wingman_name')+'</strong> <br/>Module Name: <strong>'+ data.getAttribute('module_name')+'</strong>':'')
                                    + '<br/><strong>(' + data.getAttribute('status').toUpperCase() + ')</strong>';
                    $('#event_detail').html(string);
                    $('#event_detail_new').html(string);
                    
                    if(data.getAttribute('status')== 'approved' && user_group!="Propel Wingman"){
                        $("#dialogModal").modal('show');
                    }
                    else if(data.getAttribute('status')!= 'approved'){
                        $("#dialogModal").modal('show');   
                    }
                    else if(data.getAttribute('status')=='approved' && user_group=="Propel Wingman"){
                        $('#event_detail_wingman').html(string);
                        $('#wingManDialogModal').modal('show');
                    }
                    
                    event_status = data.getAttribute('status');
                    if(event_status!='cancelled'){
                        $('#cancelEvent').show();
                        $('#cancelEventApproved').show();
                    }
                    else{
                        $('#cancelEvent').hide();
                        $('#cancelEventApproved').hide();
                    }
                    event_id = id;
                    start_date = $.datepicker.formatDate('dd-mm-yy',new Date(data.getAttribute('start')));
                    end_date = $.datepicker.formatDate('dd-mm-yy',new Date(data.getAttribute('end')));
                    start_time = timeFormat(data.getAttribute('start'));
                    end_time = timeFormat(data.getAttribute('end'));
                    volunteer_id = (data.getAttribute('volunteer_id')?data.getAttribute('volunteer_id'):'');
                    event_type = data.name;
                    
                    module_id = (data.getAttribute('module_id')?data.getAttribute('module_id'):'');
                    subject_id = (data.getAttribute('subject_id')?data.getAttribute('subject_id'):'');
                },
                eventRender: function(event, element,view) {
                    $(element).tooltip();
                    /*$('td.fc-other-month').css({
                        borderLeft:'none',
                        borderRight:'none',
                    });  
                    $('td.fc-other-month').html('');
                            //if(event.start.getMonth() !== view.start.getMonth()) { return false; }*/
                    }
                });
            
            today = document.getElementsByClassName('fc-today').item(0);
            today_date = new Date(today.getAttribute('data-date'));
            user_group = "<?php echo $user_group; ?>";
                


        });
        
        function timeFormat(time){
            var time_value = new Date(time);
            var hours = time_value.getUTCHours();
            var minutes = time_value.getUTCMinutes();
            var sec = time_value.getUTCSeconds();
            var dd = 'AM';
            var h = hours;
            if(h>=12){
                h = hours-12;
                dd = 'PM';
            }
            if(h == 0){
                h = 12;
            }
            //Converting to 2 Digit Format.
            minutes = minutes<10?"0"+minutes:minutes;
            sec = sec<10?"0"+sec:sec;
            h = h<10?"0"+h:h;
            /*var pattern = new RegExp("0?"+hours+":"+minutes+":"+sec);
            var replacement = h+":"+minutes;
            replacement += " "+dd;*/    
            var time_new = h+':'+minutes+' '+dd;
            return(time_new);           
        }

    </script>
@stop

@section('body')




<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Calendar</h2>

        <br>
        
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" id="errorCalendar" style="display:none; max-width:400px; margin:auto" role="alert"></div>
                <br/>
        
                <div class="form-group" style="max-width:900px; margin:auto">
                    <span class="" style="min-width:50px; padding:0 5px; float:left; color:#FFF"><strong>Student Name: {{$student_name}}</strong></span>
                    <span class="fc-event legend" style="min-width:50px; padding:0 5px; float:right; margin-left:10px;">Not Approved Event</span>
                    <span class="fc-event cancelled legend" style="min-width:50px; padding:0 5px; float:right; margin-left:10px">Cancelled Event</span>
                    <span class="fc-event approved legend" style="min-width:50px; padding:0 5px; float:right;">Approved Event</span>
                    <br/><br/>
                </div>
                <div id='calendar'>
                </div>
            </div>
            <div class="col-md-12">
                @if($user_group!='Propel Wingman')
                    <button type="submit" class="btn btn-default" onclick="getMonthCal()">Approve Calendar</button>
                    <br/><br/>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create Event</h4>
            </div>
            <div class="modal-body">
                <form method="post" name="propel_calender" enctype="multipart/form-data" action="{{URL::to('/calendar/createEvent')}}">
                    <div class="form-group">
                        <label for="type" class="control-label">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value=""></option>
                            <option value="child_busy">Child Busy</option>
                            <option value="volunteer_time">ASV Time</option>
                            <option value="wingman_time">Wingman Time</option>
                        </select>
                    </div>


                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="volunteer" class="control-label">Volunteer</label>
                        <select class="form-control" id="volunteer" name="volunteer_id">
                            @foreach($volunteers as $volunteer)
                                <option value="{{$volunteer->id}}">{{ucwords(strtolower($volunteer->name))}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="subject" class="control-label">Subject</label>
                        <select class="form-control" id="subject" name="subject">
                            @foreach($subjects as $subject)
                                <option value="{{$subject->id}}">{{$subject->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group optional wingman-time" style="display:none">
                        <label for="wingman_module" class="control-label">Wingman Module : </label>
                        <select class="form-control" id="wingman_module" name="wingman_module">
                            @foreach($wingman_modules as $wingman_module)
                                <option value="{{$wingman_module->id}}">{{$wingman_module->name}}</option>
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
                    <input type="hidden" id="end_date" name="end_date">
                    <input type="hidden" name="student_id" value="{{$student_id}}">
                    <input type="hidden" name="wingman_id" value="{{$wingman_id}}">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Edit Event</h4>
            </div>
            <div class="modal-body">
                <form method="post" name="propel_calender" enctype="multipart/form-data" action="{{URL::to('/calendar/editEvent')}}">
                    <div class="form-group">
                        <label for="type" class="control-label">Type</label>
                        <select class="form-control" id="edit_type" name="edit_type">
                            <option value=""></option>
                            <option value="child_busy">Child Busy</option>
                            <option value="volunteer_time">ASV Time</option>
                            <option value="wingman_time">Wingman Time</option>
                        </select>
                    </div>


                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="volunteer" class="control-label">Volunteer</label>
                        <select class="form-control" id="edit_volunteer" name="edit_volunteer">
                            @foreach($volunteers as $volunteer)
                                <option value="{{$volunteer->id}}">{{ucwords(strtolower($volunteer->name))}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="subject" class="control-label">Subject</label>
                        <select class="form-control" id="edit_subject" name="edit_subject">
                            @foreach($subjects as $subject)
                                <option value="{{$subject->id}}">{{$subject->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group optional wingman-time" style="display:none">
                        <label for="wingman_module" class="control-label">Wingman Module : </label>
                        <select class="form-control" id="edit_wingman_module" name="edit_wingman_module">
                            @foreach($wingman_modules as $wingman_module)
                                <option value="{{$wingman_module->id}}">{{$wingman_module->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_time" class="control-label">Start Time : </label>
                        <div class="form-group">
                            <input type="text" id='edit_start_date' name="edit_start_date" class="form-control" style="width: 25%" placeholder="Start Date">
                        </div>
                        <div class="form-group">
                            <input type="text" id='edit_start_time' name="edit_start_time" class="form-control" style="width: 25%" placeholder="Start Time">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="control-label">End Time : </label>
                        <div class="form-group">
                            <input type="text" id='edit_end_date' name="edit_end_date" class="form-control" style="width: 25%" placeholder="End Date">
                        </div>
                        <div class="form-group">
                            <input type="text" id='edit_end_time' name="edit_end_time" class="form-control" style="width: 25%" placeholder="End Time">
                        </div>
                    </div>

                    <input type="hidden" id="on_date" name="on_date">
                    <input type="hidden" id="end_date" name="end_date">
                    <input type="hidden" name="edit_student_id" value="{{$student_id}}">
                    <input type="hidden" name="edit_wingman_id" value="{{$wingman_id}}">
                    <input type="hidden" id="calendar_id" name="calendar_id">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update changes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="rescheduleModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Reschedule Event</h4>
            </div>
            <div class="modal-body">
                <form method="post" name="propel_calender" enctype="multipart/form-data" action="{{URL::to('/calendar/rescheduleEvent')}}">
                    
                    <div class="form-group">
                        <label for="start_time" class="control-label">Start Time : </label>
                        <div class="form-group">
                            <input type="text" id='reschedule_start_date' name="reschedule_start_date" class="form-control" style="width: 25%" placeholder="Start Date">
                        </div>
                        <div class="form-group">
                            <input type="text" id='reschedule_start_time' name="reschedule_start_time" class="form-control" style="width: 25%" placeholder="Start Time">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="control-label">End Time : </label>
                        <div class="form-group">
                            <input type="text" id='reschedule_end_date' name="reschedule_end_date" class="form-control" style="width: 25%" placeholder="End Date">
                        </div>
                        <div class="form-group">
                            <input type="text" id='reschedule_end_time' name="reschedule_end_time" class="form-control" style="width: 25%" placeholder="End Time">
                        </div>
                    </div>
                    
                    <input type="hidden" id="reschedule_subject" name="reschedule_subject">
                    <input type="hidden" id="reschedule_wingman_module" name="reschedule_wingman_module">
                    <input type="hidden" id="reschedule_volunteer" name="reschedule_volunteer">
                    
                    <input type="hidden" id="reschedule_event_type" name="reschedule_event_type">

                    <input type="hidden" id="reschedule_on_date" name="on_date">
                    <input type="hidden" id="reschedule_on_date" name="end_date">
                    <input type="hidden" name="reschedule_student_id" value="{{$student_id}}">
                    <input type="hidden" name="reschedule_wingman_id" value="{{$wingman_id}}">
                    <input type="hidden" id="rescheduleCalendar_id" name="rescheduleCalendar_id">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update changes</button>
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
            <form method="post" enctype="multipart/form-data" action="{{URL::to('/calendar/cancelEvent')}}">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="type" class="control-label">Reason : </label>
                        <select class="form-control" id="type" name="reason">
                            <option value="student_not_available">Student Not Available</option>
                            <option value="volunteer_not_available">Volunteer Not Available</option>
                            <option value="mistaken_entry">Mistaken Entry</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="comment" class="control-label">Comment : </label>
                        <textarea class="form-control" id="comment" name="comment"></textarea>
                    </div>
                    <input type="hidden" id="calendar_event_id" name="calendar_event_id">
                    <input type="hidden" id="cancel_on_date" name="cancel_on_date">
                    <input type="hidden" name="student_id" value="{{$student_id}}">
                    <input type="hidden" name="wingman_id" value="{{$wingman_id}}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="newDialogModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Select Option</h4>
            </div>
            <div class="modal-body" id="event_detail_new">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="cancelEventApproved">Cancel Event</button>
                <button type="button" class="btn btn-primary" id="rescheduleEvent">Reschedule Event</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="dialogModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Select Option</h4>
            </div>
            <div class="modal-body" id="event_detail">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="cancelEvent">Cancel Event</button>
                <button type="button" class="btn btn-primary" id="editEvent">Edit Event</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="wingManDialogModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Select Option</h4>
            </div>
            <div class="modal-body" id="event_detail_wingman">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="cancelEvent">Cancel Event</button>
                <button type="button" class="btn btn-primary" id="rescheduleEvent">Reschedule Event</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    $(document).ready(function(){
        $('#start_time').pickatime({
            min: [5,00],
            max: [22,0]
        });
        
        $('#end_time').pickatime({
            min: [5,00],
            max: [22,0]
        });
        
        $('#edit_start_time').pickatime({
            min: [5,00],
            max: [22,0]
        });
        $('#edit_start_date').pickadate({
            format: 'dd-mm-yyyy'
        });

        $('#reschedule_start_time').pickatime({
            min: [5,00],
            max: [22,0]
        });
        
        $('#reschedule_start_date').pickadate({
            format: 'dd-mm-yyyy'
        });

        $('#edit_end_time').pickatime({
            min: [5,00],
            max: [22,0]
        });

        $('#edit_end_date').pickadate({
            format: 'dd-mm-yyyy'
        });

        $('#reschedule_end_time').pickatime({
            min: [5,00],
            max: [22,0]
        });
                
        $('#reschedule_end_date').pickadate({
            format: 'dd-mm-yyyy'
        });

        $('.list_popover').popover({'html' : true});

    });

    $('#cancelEvent,#cancelEventApproved').click(function(){
        $('#dialogModal').modal('hide');
        $('#newDialogModal').modal('hide');
        $('#cancelModal').modal('show');
    });

    $('#editEvent').click(function(){
        $('#dialogModal').modal('hide');
        $('.optional').css('display','none');
        
        $('#edit_start_date').val(start_date);
        $('#edit_start_time').val(start_time);
        $('#edit_end_date').val(end_date);
        $('#edit_end_time').val(end_time);
        
        if(event_type == "ASV Time") {
            $('.volunteer-time').css('display','block');
            $('#edit_type').val('volunteer_time');
            $('#edit_volunteer').val(volunteer_id);
            $('#edit_subject').val(subject_id);
        } else if(event_type == "Wingman Time") {
            $('.wingman-time').css('display','block');
            $('#edit_type').val('wingman_time');
            $('#edit_wingman_module').val(module_id);
        }
        else{
            $('#edit_type').val('child_busy');
        }
        $('#calendar_id').val(event_id);
        $('#edit_volunteer').val(volunteer_id);
        $('#editModal').modal('show');
    });

    $('#rescheduleEvent').click(function(){
        $('#newDialogModal').modal('hide');
        $('.optional').css('display','none');
        
        if(event_type == "ASV Time") {
            $('#reschedule_event_type').val('volunteer_time');
            $('#reschedule_volunteer').val(volunteer_id);
            $('#reschedule_subject').val(subject_id);
        } else if(event_type == "Wingman Time") {
            $('#reschedule_event_type').val('wingman_time');
            $('#reschedule_wingman_module').val(module_id);
        }
        else{
            $('#reschedule_event_type').val('child_busy');
        }

        $('#reschedule_start_date').val(start_date);
        $('#reschedule_start_time').val(start_time);
        $('#reschedule_end_date').val(end_date);
        $('#reschedule_end_time').val(end_time);
        
        $('#rescheduleCalendar_id').val(event_id);
        $('#rescheduleModal').modal('show');
    });


</script>

<script src="{{URL::to('/')}}/js/picker.js"></script>
<script src="{{URL::to('/')}}/js/picker.date.js"></script>
<script src="{{URL::to('/')}}/js/picker.time.js"></script>
<script>
    $(function(){
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

        $("#edit_type").change(function () {
            // hide all optional elements
            $('.optional').css('display','none');

            $("#edit_type option:selected").each(function () {
                if($(this).val() == "volunteer_time") {
                    $('.volunteer-time').css('display','block');
                } else if($(this).val() == "wingman_time") {
                    $('.wingman-time').css('display','block');
                }
            });
        });

    });

    function getMonthCal(){
      var date = $("#calendar").fullCalendar('getDate');
      var month = Date.parse(date);
      var date = new Date (month);
      var monthValue = parseInt(date.getMonth())+1;
      var yearValue = parseInt(date.getFullYear());
      var student_id = {{$student_id}};
      var href = "{{URL::to('/')}}/calendar/approve/" + student_id + '/' + monthValue + '/' + yearValue;
      window.location.assign(href);
    }

</script>

@stop

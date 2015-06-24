@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.time.css" id="theme_date">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/calendar.css" id="theme_date">
<link href='{{{URL::to("/")}}}/css/fullcalendar.css' rel='stylesheet' />
<link href='{{{URL::to("/")}}}/css/fullcalendar.print.css' rel='stylesheet' media='print' />

<style>

    #calendar {
        max-width: 900px;
        margin: 0 auto;
        margin-bottom:50px;
    }

</style>
<script type="text/javascript">
    event_type;
    volunteer_id;
    module_id;
    subject_id;
    start_time;
    end_time;
    start_date;
    end_date;
    event_id;
</script>
<script src='{{{URL::to("/")}}}/js/lib/moment.min.js'></script>
<script src='{{{URL::to("/")}}}/js/fullcalendar.js'></script>
<script type="text/javascript">

    $(document).ready(function() {
            
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                <?php
                    if(isset($_aET['date'])){
                        echo 'defaultDate: \''.$_GET['date'].'\',';
                    }
                ?>
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var title=' ';
                    var start_date = new Date(start);
                    var end_date = new Date(end);

                    //End day returned by function is one day ahead, hence subtracting one day
                    end_date.setDate(end_date.getDate()-1);

                    start_date = $.datepicker.formatDate('yy-mm-dd',start_date);
                    end_date = $.datepicker.formatDate('yy-mm-dd',end_date);

                    $("#on_date").val(start_date);
                    $("#end_date").val(end_date);

                    var start_time = timeFormat(start);
                    var end_time = timeFormat(end);
                    $("#createModal").modal('show');
                    $('#start_time').val(start_time);
                    $('#end_time').val(end_time);
                    var eventData;
                    if (title) {
                        eventData = {
                            id:id,
                            title: title,
                            start: start,
                            end: end
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
                    var user_group = "<?php echo $user_group; ?>";
                    var data = document.getElementById(id.toString());
                    $('#calendar_event_id').val(id);
                    var string = '<strong>'+ data.name + '</strong>'
                                    + '<br/>' + data.getAttribute('start')
                                    + ' - ' + data.getAttribute('end')
                                    + (data.getAttribute('volunteer_name')?'<br/>Volunteer Name: <strong>'+data.getAttribute('volunteer_name')+'</strong><br/>Subject Name: <strong>'+data.getAttribute('subject_name')+'</strong>':'')
                                    + (data.getAttribute('wingman_name')?'<br/>Wingman Name: <strong>'+data.getAttribute('wingman_name')+'</strong> <br/>Module Name: <strong>'+ data.getAttribute('module_name')+'</strong>':'')
                                    + '<br/><strong>(' + data.getAttribute('status').toUpperCase() + ')</strong>';
                    $('#event_detail').html(string);
                    if(data.getAttribute('status')== 'approved' && user_group!="Propel Wingman"){
                        $("#dialogModal").modal('show');
                    }
                    else if(data.getAttribute('status')!= 'approved'){
                        $("#dialogModal").modal('show');   
                    }
                    
                    event_id = id;
                    start_time = data.getAttribute('start');
                    end_time = data.getAttribute('end');
                    volunteer_id = (data.getAttribute('volunteer_id')?data.getAttribute('volunteer_id'):'');
                    event_type = data.name;
                    module_id = (data.getAttribute('module_id')?data.getAttribute('module_id'):'');
                    subject_id = (data.getAttribute('subject_id')?data.getAttribute('subject_id'):'');
                },
                eventRender: function(event, element) {
                    $(element).tooltip();
                }
            });
        
            $('#calendar.cancelled').click(function(e) {
                e.preventDefault() ;
            }) ;
        });
        
        function timeFormat(time){
            var time_value = new Date(time);
            var hours = time_value.getUTCHours();
            var minutes = time_value.getUTCMinutes();
            var sec = time_value.getUTCSeconds();
            var dd = 'AM';
            var h = hours;;
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
                <div class="form-group" style="max-width:900px; margin:auto">

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
                <form method="post" name="propel_calender" enctype="multipart/form-data" action="{{{URL::to('/calendar/asv/createEvent')}}}">


                    <div class="form-group">
                        <label for="student" class="control-label">Students : </label>
                        <select multiple class="form-control" name="student_id[]">
                            @foreach($students as $student)
                                <option value="{{{$student->id}}}">{{{$student->name}}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="control-label">Subject</label>
                        <select class="form-control" id="subject" name="subject">
                            @foreach($subjects as $subject)
                            <option value="{{{$subject->id}}}">{{{$subject->name}}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_time" class="control-label">Start Time : </label>

                        <div class="form-group">
                            <input type="text" id='start_time' name="start_time" class="form-control" style="width: 25%"
                                   placeholder="Start Time">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="control-label">End Time : </label>

                        <div class="form-group">
                            <input type="text" id='end_time' name="end_time" class="form-control" style="width: 25%"
                                   placeholder="End Time">
                        </div>
                    </div>

                    <input type="hidden" name="volunteer_id" value="{{{$volunteer_id}}}">
                    <input type="hidden" name="type" value="volunteer_time">
                    <input type="hidden" id="on_date" name="on_date">
                    <input type="hidden" id="end_date" name="end_date">


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
            <form method="post" enctype="multipart/form-data" action="{{{URL::to('/calendar/asv/cancelEvent')}}}">
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
                    <input type="hidden" name="volunteer_id" value="{{{$volunteer_id}}}">
                    <input type="hidden" id="calendar_event_id" name="calendar_event_id">
                    <input type="hidden" id="cancel_on_date" name="cancel_on_date">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                
            </div>
            </form>
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
            min: [5,00],
            max: [22,0]
        });
        $('#edit_end_time').pickatime({
            min: [5,00],
            max: [22,0]
        });
        $('#edit_end_date').pickadate({
            min: [5,00],
            max: [22,0]
        });
        $('.list_popover').popover({'html' : true});

    });

    $('#cancelEvent').click(function(){
        $('#dialogModal').modal('hide');
        $('#cancelModal').modal('show');
    });

    $('#editEvent').click(function(){
        $('#dialogModal').modal('hide');
        $('.optional').css('display','none');
        if(event_type == "Volunteer Time") {
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

</script>

<script src="{{{URL::to('/')}}}/js/picker.js"></script>
<script src="{{{URL::to('/')}}}/js/picker.date.js"></script>
<script src="{{{URL::to('/')}}}/js/picker.time.js"></script>
<script>


    function getMonthCal(){
      var date = $("#calendar").fullCalendar('getDate');
      var month = Date.parse(date);
      var date = new Date (month);
      var monthValue = parseInt(date.getMonth())+1;
      var yearValue = parseInt(date.getFullYear());
      window.location.assign(href);
    }

</script>

@stop

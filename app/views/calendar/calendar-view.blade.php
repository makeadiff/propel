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
                //defaultDate: '2015-02-12',
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var cur_date = $.datepicker.formatDate('yy-mm-dd',new Date(start));
                    $("#on_date").val(cur_date);
                    var start_time = timeFormat(start);
                    var end_time = timeFormat(end);
                    $("#createEditModal").modal('show');
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
                
                events: <?php echo $calendarEvents ?>
            });
        
            $('.fc-content').click(function(){
                $("#cancelModal").modal('show');
                
            })
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
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="createEditModal" tabindex="-1" role="dialog" aria-labelledby="createEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create Event</h4>
            </div>
            <div class="modal-body">
                <form method="post" name="propel_calender" enctype="multipart/form-data" action="{{{URL::to('/calendar/createEdit')}}}">
                    <div class="form-group">
                        <label for="type" class="control-label">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value=""></option>
                            <option value="child_busy">Child Busy</option>
                            <option value="volunteer_time">Volunteer Time</option>
                            <option value="wingman_time">Wingman Time</option>
                        </select>
                    </div>


                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="volunteer" class="control-label">Volunteer</label>
                        <select class="form-control" id="volunteer" name="volunteer">
                            @foreach($volunteers as $volunteer)
                                <option value="{{{$volunteer->id}}}">{{{$volunteer->name}}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group optional volunteer-time" style="display:none">
                        <label for="subject" class="control-label">Subject</label>
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
                            <option value="mistaken_entry">Mistaken Entry</option>
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

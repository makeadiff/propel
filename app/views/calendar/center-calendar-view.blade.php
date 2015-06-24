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
            <?php
                if(isset($_aET['date'])){
                    echo 'defaultDate: \''.$_GET['date'].'\',';
                }
            ?>



            editable: false,
            eventLimit: false, // allow "more" link when too many events

            events: <?php echo $calendarEvents ?>,


            eventRender: function(event, element) {
                $(element).tooltip();
            }
        });

    });



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

        </div>
    </div>
</div>


@stop

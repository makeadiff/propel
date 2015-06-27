@extends('layouts.master')

@section('body')

<div class="container-fluid">

        <br>
        <br>
        <h1 class="title text-center">Propel</h1>
        <br>
        <div class="row">

        @if($user_group == "Propel Strat" || $user_group == "Program Director, Propel")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='city-change/city-select' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/cities.png')}}}"><br>Cities</a>
            </div>
        @endif

        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='wingman-journal/select-wingman' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/journal.png')}}}"><br>Journals of<br>Wingmen</a>
            </div>
            <div class="col-md-4 col-sm-6 text-center">
                <a href='feedback/select-student' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/reports.png')}}}"><br>Child<br>Feedback</a>
            </div>
            <div class="col-md-4 col-sm-6 text-center">
                <a href='feedback/module-feedback' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/feedback.png')}}}"><br>Module<br>Feedback</a>
            </div>
        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='wingman-journal/{{{$user->id}}}' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/journal.png')}}}"><br>Wingman<br>Journal</a>
            </div>
            <div class="col-md-4 col-sm-6 text-center">
                <a href='feedback/{{{$user->id}}}' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/reports.png')}}}"><br>Child<br>Feedback</a>
            </div>
        @endif


        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/select-wingman' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/calendar.png')}}}"><br>Calendars of<br>Wingmen</a>
            </div>

            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/select-asv' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/asv.png')}}}"><br>Calendars of<br>ASVs</a>
            </div>
            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/select-center' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/centers.png')}}}"><br>Calendars of<br>Centers</a>
            </div>
        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/{{{$user->id}}}' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/calendar.png')}}}"><br>Calendar of<br>Students</a>
            </div>
            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/select-asv' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/asv.png')}}}"><br>Calendars of<br>ASVs</a>
            </div>
        @endif

        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/approve-calendar' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/approve.png')}}}"><br>Pending<br>Approvals</a>
            </div>
        @endif

        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='attendance/select-wingman' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/attendance.png')}}}"><br>Attendance of<br>Wingmen</a>
            </div>
        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='attendance/{{{$user->id}}}' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/attendance.png')}}}"><br>Attendance</a>
            </div>
        @endif

        <div class="col-md-4 col-sm-6 text-center">
            <a href='http://makeadiff.in/apps/okr' target="_blank" class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/okr.png')}}}"><br>OKR</a>
        </div>

        @if($user_group == "Propel Fellow")

            <div class="col-md-4 col-sm-6 text-center">
                <a href='http://makeadiff.in/madapp/index.php/kids/manageaddkids' target='_blank' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/kids.png')}}}"><br>Students</a>
            </div>

            <div class="col-md-4 col-sm-6 text-center">
                <a href='settings/wingmen' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/wingman.png')}}}"><br>Assign<br>Wingmen</a>
            </div>

            <div class="col-md-4 col-sm-6 text-center">
                <a href='settings/select-wingman' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/students.png')}}}"><br>Assign<br>Students<br>to Wingmen</a>
            </div>

            <div class="col-md-4 col-sm-6 text-center">
                <a href='settings/subjects' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/subjects.png')}}}"><br>Assign<br>Subjects</a>
            </div>



        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='settings/students' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/students.png')}}}"><br>Assign<br>Students</a>
            </div>
        @endif


        @if($user_group == "Propel Strat" || $user_group == "Program Director, Propel" || $user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a target="_blank" href='http://makeadiff.in/madapp/index.php/event/event' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/events.png')}}}"><br>Events</a>
            </div>
        @endif

        @if($user_group == "Propel Strat" || $user_group == "Program Director, Propel")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='reports' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/reports.png')}}}"><br>Reports</a>
            </div>
        @endif

    </div>
</div>
@stop

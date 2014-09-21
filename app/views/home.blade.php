@extends('layouts.master')

@section('body')

<div class="container-fluid">

        <br>
        <br>
        <h1 class="title text-center">Propel</h1>
        <br>
        <div class="row">

        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='wingman-journal/select-wingman' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/journal.png')}}}"><br>Journals of Wingmen</a>
            </div>
        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='wingman-journal/{{{$user->id}}}' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/journal.png')}}}"><br>Wingman Journal</a>
            </div>
        @endif


        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/select-wingman' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/calendar.png')}}}"><br>Calendars of Wingmen</a>
            </div>
        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='calendar/{{{$user->id}}}' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/calendar.png')}}}"><br>Calendar</a>
            </div>
        @endif


        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='attendance/select-wingman' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/attendance.png')}}}"><br>Attendance of Wingmen</a>
            </div>
        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='attendance/{{{$user->id}}}' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/attendance.png')}}}"><br>Attendance</a>
            </div>
        @endif

        @if($user_group == "Propel Fellow")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='settings/wingmen' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/wingman.png')}}}"><br>Assign Wingmen</a>
            </div>

            <div class="col-md-4 col-sm-6 text-center">
                <a href='settings/subjects' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/subjects.png')}}}"><br>Assign Subjects</a>
            </div>
        @elseif($user_group == "Propel Wingman")
            <div class="col-md-4 col-sm-6 text-center">
                <a href='settings/students' class='btn btn-primary btn-dash transparent'><img src="{{{URL::to('/img/students.png')}}}"><br>Assign Students</a>
            </div>
        @endif

    </div>
</div>
@stop

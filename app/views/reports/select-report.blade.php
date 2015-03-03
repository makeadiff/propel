@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Reports</h2>
        <br>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">

                <a class="btn btn-primary" href="reports/class-status/select-city">Class Status</a><br><br>
                <a class="btn btn-primary" href="reports/wingman-journal-report">Wingman Journal</a><br><br>
                <a class="btn btn-primary" href="reports/attendance-report">Attendance</a><br>


            </div>
            <br>

            <br>
        </div>
    </div>
</div>


@stop

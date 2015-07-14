@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Class Status</h2>
        <br>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">

                <table class="table table-responsive table-bordered white   ">
                    <tr>
                        <th>Volunteer/Wingman</th><th>Class Attended / Classes Scheduled</th>
                    </tr>
                    @foreach($wingmen as $wingman)
                        <tr>
                            <td>{{{$wingman->name}}}</td>
                            <td>{{{$wingman->classes_attended}}} / {{{$wingman->total_classes}}}</td>
                        </tr>
                    @endforeach

                    @foreach($volunteers as $volunteer)
                    <tr>
                        <td>{{{$volunteer->name}}}</td>
                        <td>{{{$volunteer->classes_attended}}} / {{{$volunteer->total_classes}}}</td>
                    </tr>
                    @endforeach
                </table>

            </div>
            <br>

            <br>
        </div>
    </div>
</div>

@stop

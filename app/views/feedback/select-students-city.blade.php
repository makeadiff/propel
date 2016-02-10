@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="centered">
        <div class="row">

            <h2 class="sub-title">Select Students</h2>
            <br>


            @foreach($students as $student)
                <div style="padding:10px" class="col-md-4 col-sm-6 text-center">
                    <a href="{{URL::to('/feedback/'. $student->wingman_id.'/'.$student->student_id)}}" class='btn btn-primary btn-dash transparent'><img  src="{{URL::to('/img/profile.png')}}"><br/>{{ucwords(strtolower($student->name))}}</a>
                </div>
            @endforeach
        

        </div>

    </div>

</div>




@stop

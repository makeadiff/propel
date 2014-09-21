@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{{URL::to('/')}}}/css/default.date.css" id="theme_date">
@stop


@section('body')

<div class="container-fluid">


        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 class="sub-title">New Wingman Journal</h2><br>
                <form id="journal-entry" role="form" method="post" enctype="multipart/form-data" action="{{{URL::to('/')}}}/journal-entry">

                    <h4 class="sub-title">Student : </h4>
                    <div class="form-group">
                        <select id="student" class="form-control" placeholder="Student" name="student" style="width: 25%">
                            @foreach($students as $student)
                                <option value="{{{$student->id}}}">{{{$student->name}}}</option>
                            @endforeach
                        </select>
                    </div>

                    <h4 class="sub-title">Type : </h4>
                    <div class="form-group white">
                        <label><input name="type" type="radio" id="formal" checked="checked" value="formal">Formal</label>&nbsp; &nbsp;
                        <label><input name="type" type="radio" id="informal" value="informal">Informal</label>
                    </div>

                    <div class="form-group">
                        <input type="text" id='pickdate' name="pickdate" class="form-control" style="width: 25%" placeholder="Date">
                    </div>

                    <div class="form-group" >
                        <input type="text" class="form-control" name="title" id="title" value="" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="mom" placeholder="Minutes of Meeting" rows="10"></textarea>
                    </div>
                    <div class="centered">
                        <button type="submit" class="btn btn-primary text-center">Save</button>
                    </div>
                </form>
            </div>

        </div>

</div>

<script>
    $(document).ready(function(){
        $('#pickdate').pickadate();
    });
</script>
<script src="{{{URL::to('/')}}}/js/picker.js"></script>
<script src="{{{URL::to('/')}}}/js/picker.date.js"></script>


@stop

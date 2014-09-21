@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Choose Wingmen</h2>
        <br>
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
            <form action="" method="post">
            {{
                Form::select('wingmen[]',
                $all_wingmen->lists('name', 'id'), 
                $selected_wingmen_id,
                array('multiple'=>true, 'size'=>10)); 
            }}<br />
            <input type="submit" name="action" class="btn btn-primary" value="Save" />
            </form>
        </div>
        <br>

        <br>
        </div>
    </div>
</div>

@stop
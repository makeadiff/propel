@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Attendence</h2>
        <br>

        <div class="row">
            <div class="col-md-offset-2 col-md-8">
            <form action="" method="post">
            <table class="table table-bordered table-responsive white">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Volunteer</th>
                    <th>Attended</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($attended as $entry)
                        <tr>
                        <td>{{{date_format(date_create($entry->start_time),'l, jS F Y')}}}</td>
                        <td>{{{ ucwords(str_replace('_',' ',$entry->type)) }}}</td>
                        @if($entry->type == "volunteer_time")
                        <td>{{{$entry->volunteerTime()->first()->volunteer()->first()->name}}}</td>
                        @else
                        <td>{{{$entry->wingmanTime()->first()->wingman()->first()->name}}}</td>
                        @endif

                        <td>
                        <input {{{($entry->status == "attended" ? 'checked' : "")}}} type="checkbox" value="1" name="attended[{{{$entry->id}}}]" />
                        <input type="hidden" value="1" name="calender_entry[{{{$entry->id}}}]" />
                        </td>
                        </tr>
                    @endforeach

                    <tr><td colspan="3"></td><td><input type="submit" value="Save" name="action" class="btn btn-success" /></td></tr>
                </tbody>
            </table>
            </form>
        </div>
        <br>

        <br>
        </div>
    </div>
</div>

<script>
    function checkDelete(id) {
        if (confirm('Really delete?')) {
                $.ajax({
                type: "DELETE",
                url: '{{{URL::to('/')}}}' + '/attendence-entry/' + id,
                complete: function(result) {
                    location.reload(true);
            }
            });

        }
    }
</script>
@stop

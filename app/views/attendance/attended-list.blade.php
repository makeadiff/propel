@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Attendance</h2>
        <br>
        @if(count($attended)!=0)
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
            <form action="" method="post">
            <table class="table table-bordered table-responsive white">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Volunteer/Wingman</th>
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
                        @elseif($entry->type == "wingman_time")
                        <td>{{{$entry->wingmanTime()->first()->wingman()->first()->name}}}</td>
                        @endif

                        <td>
                        <input {{{($entry->status == "attended" ? 'checked' : "")}}} type="checkbox" value="1" name="attended[{{{$entry->id}}}]" />
                        <input type="hidden" value="1" name="calender_entry[{{{$entry->id}}}]" />
                        </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
                <input type="submit" value="Save" name="action" class="btn btn-primary" />
            </form>
        </div>
        <br>

        <br>
        </div>
        @else
        <p style="text-align:center; color:#FFF">No attendance entries to mark</p><br/><br/>

        @endif
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

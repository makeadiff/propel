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
                        <?php 
                            $type = $entry->type;
                            if($type=='volunteer_time'){
                                $variable = $entry->volunteerTime()->first();
                                if(!empty($variable))
                                {
                                    $type = $entry->volunteerTime()->first()->volunteer()->first()->user_type; 
                                    $status = $entry->volunteerTime()->first()->volunteer()->first()->status;
                                }
                            }
                            elseif ($type=='wingman_time'){
                                $variable = $entry->wingmanTime()->first();
                                if(!empty($variable))
                                {
                                    $type = $entry->wingmanTime()->first()->wingman()->first()->user_type;
                                    $status = $entry->wingmanTime()->first()->wingman()->first()->status;
                                }
                            }                           
                        ?>
                        @if(!empty($variable) && ($type=='volunteer' && $status=='1'))
                            <tr>
                            <td>{{date_format(date_create($entry->start_time),'l, jS F Y')}}</td>
                            <td>{{ ucwords(str_replace('_',' ',$entry->type)) }}</td>
                            @if($entry->type == "volunteer_time")
                            <td>
                                <?php 
                                    $type = $entry->volunteerTime()->first()->volunteer()->first()->user_type; 
                                    $status = $entry->volunteerTime()->first()->volunteer()->first()->status;
                                    $name = $entry->volunteerTime()->first()->volunteer()->first()->name;
                                    if($type=='volunteer' && $status=='1')
                                        echo $name;
                                ?>
                            </td>
                            @elseif($entry->type == "wingman_time")
                            <td>
                                <?php
                                    $type = $entry->wingmanTime()->first()->wingman()->first()->user_type;
                                    $status = $entry->wingmanTime()->first()->wingman()->first()->status;
                                    $name = $entry->wingmanTime()->first()->wingman()->first()->name;
                                    if($type=='volunteer' && $status=='1')
                                        echo $name;
                                ?>
                            </td>
                            @endif

                            <td>
                            <input {{($entry->status == "attended" ? 'checked' : "")}} type="checkbox" value="1" name="attended[{{$entry->id}}]" />
                            <input type="hidden" value="1" name="calender_entry[{{$entry->id}}]" />
                            </td>
                            </tr>
                        @endif
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
                url: '{{URL::to('/')}}' + '/attendence-entry/' + id,
                complete: function(result) {
                    location.reload(true);
            }
            });

        }
    }
</script>
@stop

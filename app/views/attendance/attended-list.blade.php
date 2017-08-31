@extends('layouts.master')

@section('body')


<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<style>
  .toggle.btn{
    height: 25px !important;
  }
</style>

<link href="{{URL::to('css/bootstrap-toggle.min.css')}}" rel="stylesheet">

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Attendance</h2>
        <br>
        @if(count($attended)!=0)
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
            <form action="" method="post">
            <table class="table table-bordered table-responsive white footable" data-paging="true" data-page-size="12">
                <thead>
                <tr>
                    <th width="40%">Wingman Name</th>
                    <th width="40%">Date</th>
                    <th width="20%">Attended</th>
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
                            else
                            if ($type=='wingman_time'){
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

                            <!-- <td>{{ ucwords(str_replace('_',' ',$entry->type)) }}</td> -->
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
                            <td>{{date_format(date_create($entry->start_time),'D, jS M Y')}}</td>
                            <td>
                            <input {{($entry->status == "attended" ? 'checked' : "")}} type="checkbox" data-toggle="toggle" data-on="Present" data-off="Absent" value="1" name="attended[{{$entry->id}}]" />
                            <input type="hidden" value="1" name="calender_entry[{{$entry->id}}]" />
                            </td>
                            </tr>
                        @endif
                    @endforeach


                </tbody>
                <tfoot>
                <tr class="hide-if-no-paging">
                    <td colspan="7">
                        <div class="text-center">
                            <ul class="pagination pagination-centered"></ul>
                        </div>
                    </td>
                </tr>
                </tfoot>
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
        @if()
          <a href="{{{URL::to('attendance/wingman/previous')}}}"><button type="button" class="btn btn-default" data-dismiss="modal">Mark Attendance for events before {{$date}}</button></a>
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

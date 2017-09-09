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

        <h2 class="sub-title">Wingman Attendance</h2>
        <br>
        @if(count($attended)!=0)
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
            <form action="" method="post">
              <label for="filter" class="white">FILTER&nbsp;</label>
              <input type="text" id="filter" data-filter=#filter class="form-control input-sm" placeholder="Start Typing Name/Date">
              <br/>
              <table data-filter="#filter" class="table table-bordered table-responsive white footable" data-paging="true" data-page-size="12" data-filter-timeout="100" data-filter-text-only="true" data-filter-minimum="3">
                  <thead>
                  <tr>
                      <th width="40%">Wingman Name</th>
                      <!-- <th width="30%">Student Name</th> -->
                      <th width="40%">Date</th>
                      <th width="15%">Attended</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($attended as $event)
                          <?php
                              $entry = CalendarEvent::find($event->id);
                              $type = $entry->type;
                              if($type=='wingman_time'){
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
                              @if($entry->type == "wingman_time")
                              <td>
                                  <?php
                                      $type = $entry->wingmanTime()->first()->wingman()->first()->user_type;
                                      $status = $entry->wingmanTime()->first()->wingman()->first()->status;
                                      $name = $entry->wingmanTime()->first()->wingman()->first()->name;
                                      if($type=='volunteer' && $status=='1')
                                          echo ucwords(strtolower($name));
                                  ?>
                              </td>
                              <!-- <td>
                                  <?php
                                      $student = $entry->student()->first()->name;
                                      //echo ucwords(strtolower($student));
                                  ?>
                              </td> -->
                              @endif
                              <td>{{date_format(date_create($entry->start_time),'D, j M y, H:i')}}</td>
                              <td class="center">
                              <input {{($entry->status == "attended" ? 'checked' : "")}} type="checkbox" data-toggle="toggle" data-on="Present" data-off="Absent" value="1" name="attended[{{$entry->id}}]" />
                              <input type="hidden" value="1" name="calender_entry[{{$entry->id}}]" />
                              <input type="hidden" name="volunteer_id[{{$entry->id}}]" value="{{$event->wingman_id}}"></input>
                              <input type="hidden" name="start_time[{{$entry->id}}]" value="{{$entry->start_time}}"></input>
                              <input type="hidden" name="type" value="volunteer_time"/></input>
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
        @if($timeline==null)
          <?php
            if(isset($_SESSION['original_id'])){
              $user = new HomeController;
              $usergroup = $user->getOriginalGroup();
              // echo $usergroup;
              if($usergroup=="director"){
                echo '<a href="'.(URL::to('attendance/wingman/previous')).'"><button type="button" class="btn btn-default" data-dismiss="modal">Mark Attendance for events before '.$date.'</button></a>';
              }
            }
          ?>
        @elseif($timeline=="previous")
          <?php
            if(isset($_SESSION['original_id'])){
              $user = new HomeController;
              $usergroup = $user->getOriginalGroup();
              // echo $usergroup;
              if($usergroup=="director"){
                echo '<a href="'.(URL::to('./attendance/wingman/')).'"><button type="button" class="btn btn-default" data-dismiss="modal">View Less</button></a>';
              }
            }
          ?>
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

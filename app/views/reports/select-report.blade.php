@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Reports</h2>
        <p class="white">*Note: By default, the report shows data after {{date("F j, Y, g:i a",strtotime($year_time))}}. To change the Date Range, use filters. </p>
        <br>
        <br>

        <div class="row">
            <!-- <div class="col-md-3 col-md-offset-1 text-center white">
              Wingman Adoption <br/>
              <a href='reports/attendance' class='btn-primary'><h1>{{substr($wingmen_adoption,0,4)}}%</h1></a>
            </div> -->
            <!-- <div class="col-md-4 text-center ">
              <div class="row">
                Filter Range
              </div>
              <div class="row">
                  <div class="col-md-6 text-center">
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" id='start_date' name="start_date" class="form-control" placeholder="Start Date (From)"
                                <?php
                                    // if(isset($start_date) && $start_date!="null"){
                                    //     echo 'value="'.$start_date.'"';
                                    // }
                                ?>
                            >
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6 text-center">
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" id='end_date' name="end_date" class="form-control"  placeholder="End Date (Till)"
                                <?php
                                    // if(isset($end_date) && $end_date!="null"){
                                    //     echo 'value="'.$end_date.'"';
                                    // }
                                ?>
                            >
                        </div>
                    </div>
                  </div>
              </div>
            </div> -->
            <!-- <div class="col-md-3 text-center white">s
               Fellow Adoption
               <a href='reports/attendance' class='btn-primary'><h1>{{substr($fellow_adoption,0,4)}}%</h1></a>
            </div>
            <div class="col-md-1"></div> -->
        <!-- </div> -->
        <!-- <div class="row">
          <div class="col-md-8 col-sm-12 col-md-offset-2">
            <hr/>
          </div>
        </div> -->
        <div class="row">
            <div class="col-md-3 col-sm-6 text-center">
                 <a href='reports/attendance' class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/attendance.png')}}"><br>Attendance</a>
            </div>

            <div class="col-md-3 col-sm-6 text-center">
                 <a href="{{{URL::to('reports/calendar-summary')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/calendar.png')}}"><br><br>Calendar <br/>Summary</a>
            </div>

            <div class="col-md-3 col-sm-6 text-center">
                 <a href="{{{URL::to('reports/class-cancelled-report')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/reports.png')}}"><br>Cancelled<br/>Classes</a>
            </div>

            <div class="col-md-3 col-sm-6 text-center">
                 <a href="{{{URL::to('reports/child-report')}}}" class=' btn btn-primary btn-dash transparent'><img src="{{URL::to('/img/kids.png')}}"><br><br>Child Data</a>
            </div>
            <br><br>
        </div>
    </div>
</div>


@stop

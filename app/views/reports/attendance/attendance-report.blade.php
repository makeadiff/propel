@extends('layouts.master')

@section('body')

<link rel="stylesheet" href="{{URL::to('/')}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.date.css" id="theme_date">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.time.css" id="theme_date">

<script src='{{URL::to("/")}}/js/lib/moment.min.js'></script>
<script src='{{URL::to("/")}}/js/fullcalendar.js'></script>

<script type="text/javascript">
    $(function () {
        $('.footable').footable({
            breakpoints: {

                phone: 555

            },
        });
    });
</script>

<script type="text/javascript">

    $(function () {
        $('.clear-filter').click(function (e) {
            e.preventDefault();
            $('table').trigger('footable_clear_filter');
        });
    });

</script>

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Attendance Report - {{ucwords(str_replace('_',' ',$event_type))}}</h2>
        <p class="white">*Note: By default, the report shows data only after {{date("F j, Y, g:i a",strtotime($year_time))}}. To change the Date Range, use filters. </p>
        <br>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form class="search_parameters" method="post" action="{{{URL::to('/reports/attendanceReport')}}}">
                    <input type="hidden" name="event_type" value="{{$event_type}}"/>
                    <p class="white">Select Time Duration</p>

                    <div class="row">
                        <div class='col-md-6 col-sm-12'>
                            <div class="form-group">
                                <div class="form-group">
                                    <input type="text" id='start_date' name="start_date" class="form-control" placeholder="Start Date (From)"
                                        <?php
                                            if(isset($start_date) && $start_date!="null"){
                                                echo 'value="'.$start_date.'"';
                                            }
                                        ?>

                                    >
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6 col-sm-12'>
                            <div class="form-group">
                                <div class="form-group">
                                    <input type="text" id='end_date' name="end_date" class="form-control"  placeholder="End Date (Till)"
                                        <?php
                                            if(isset($end_date) && $end_date!="null"){
                                                echo 'value="'.$end_date.'"';
                                            }
                                        ?>
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <input type="submit" value="Filter Values" />
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2 white">

            <br>
                @if(count($datas)!=0)
                <table class="white footable table table-bordered table-responsive toggle-medium attendance-report" data-filter-timeout="500" data-filter-text-only="true" data-filter-minimum="3">
                    <thead >
                    <tr>
                        <th width="40%" style="text-decoration:underline">City Name</th>
                        @if($event_type=="wingman_time")
                        <th width="15%" style="text-decoration:underline">Ideal Sessions</th>
                        @endif
                        <th width="15%" style="text-decoration:underline">Sessions scheduled</th>
                        <th width="15%" style="text-decoration:underline">Sessions Attended</th>
                        <th width="15%" style="text-decoration:underline">%Attendance</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;
                        foreach ($datas as $data) {

                            if(!isset($data['attended'])){
                                $data['attended'] = 0;
                            }
                            if(!isset($data['approved'])){
                                $data['approved'] = 0;
                            }

                            $attended = (int)$data['attended'];
                            $approved = (int)$data['approved'] + $attended;
                            if($approved!=0){
                              $percent_attended = floatval(($attended/$approved) * 100);
                            }
                            else{
                              $percent_attended = 0;
                            }

                            if(isset($start_date) && $start_date!=''){
                                $start = '/'.$start_date;
                            }
                            else{
                                $start = "/null";
                            }

                            if(isset($end_date) && $end_date!=''){
                                $end = '/'.$end_date;
                            }
                            else{
                                $end = "/null";
                            }

                            echo '<tr>'.
                            '<td><a href="'.URL::to("reports/attendance-report/".$data['city_id']."/".$event_type."".$start."".$end."").'">'.$data['city_name'].'</td>';
                            if($event_type == "wingman_time")
                              echo '<td class="right">'.$data['ideal_session'].'</td>';
                            echo '<td class="right '.($approved == 0 ? "zero":"").'">'.$approved.'</td>'.
                            '<td class="right '.($attended == 0 ? "zero":"").'">'.$attended.'</td>'.
                            '<td class="right '.(round($percent_attended,0,PHP_ROUND_HALF_DOWN)==0?"zero":"").'">'.round($percent_attended,0,PHP_ROUND_HALF_DOWN).'%</td>'.
                            '</tr>';
                        }
                    ?>
                    </tbody>

                </table>
                @else
                <div class="alert alert-warning" role="alert">No data for the selected City and Center</div>
                @endif
            </div>

            <br>
        </div>
    </div>
</div>
<script src="{{URL::to('/')}}/js/picker.js"></script>
<script src="{{URL::to('/')}}/js/picker.date.js"></script>
<script src="{{URL::to('/')}}/js/picker.time.js"></script>


<script type="text/javascript">

    $(document).ready(function(){
        $('#start_date').pickadate({
            format: 'dd-mm-yyyy'
        });

        $('#end_date').pickadate({
            format: 'dd-mm-yyyy'
        });

         $('.list_popover').popover({'html' : true});
    });

</script>

@stop

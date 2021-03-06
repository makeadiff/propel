@extends('layouts.master')

@section('body')

<link rel="stylesheet" href="{{URL::to('/')}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.date.css" id="theme_date">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.time.css" id="theme_date">

<script src='{{URL::to("/")}}/js/lib/moment.min.js'></script>
<script src='{{URL::to("/")}}/js/fullcalendar.js'></script>
<
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

        <h2 class="sub-title">Calendar Approval Summary</h2>
        <p class="white">*Note: By default, the report shows data after {{date("F j, Y, g:i a",strtotime($year_time))}}. To change the Date Range, use filters. </p>
        <br>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form class="search_parameters" method="post" action="{{{URL::to('/reports/calendarApproval')}}}">
                    <p class="white">Select Time Duration</p>

                <div class="row">
                    <div class="col-md-2 col-sm-12"></div>
                    <div class='col-md-4 col-sm-12'>
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
                    <div class='col-md-4 col-sm-12'>
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
                    <!--<div class='col-md-4 col-sm-12'>
                        <div class="form-group">
                            <div class="form-group">
                                <input type="text" id='month' name="month" class="form-control"  placeholder="Select Month"
                                    <?php
                                        /*if(isset($end_date) && $end_date!="null"){
                                            echo 'value="'.$end_date.'"';
                                        }*/
                                    ?>
                                >
                            </div>
                        </div>
                    </div>-->


                </div>
                <br/>
                    <input type="submit" value="Filter Values" />
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2     white">

            <br>
                @if(count($datas)!=0)
                <table class="white footable table table-bordered table-responsive toggle-medium" data-filter-timeout="500" data-filter-text-only="true" data-filter-minimum="3">
                    <thead >
                    <tr>
                        <th width="40%" style="text-decoration:underline">City Name</th>
                        <th width="20%" style="text-decoration:underline">Calendars Created</th>
                        <th width="20%" style="text-decoration:underline">Calendars Approved</th>
                        <th width="20%" style="text-decoration:underline">% Calendars Approved</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;
                        foreach ($datas as $data) {

                            if(!isset($data['attended'])){
                                $data['attended'] = 0;
                            }
                            if(!isset($data['created'])){
                                $data['created'] = 0;
                            }
                            if(!isset($data['approved'])){
                                $data['approved'] = 0;
                            }

                            $approved = (int)$data['approved'] + (int)$data['attended'];
                            $created = (int)$data['created'] + $approved;
                            if($created!=0)
                              $percent_approved = floatval(($approved/$created) * 100);
                            else
                              $percent_approved = 0;

                            if(isset($start_date) && $start_date!=""){
                                $start = '/'.$start_date;
                            }
                            else{
                                $start = "/null";
                            }

                            if(isset($end_date) && $end_date !=""){
                                $end = '/'.$end_date;
                            }
                            else{
                                $end = "/null";
                            }

                            //echo $start; echo $end;

                            echo '<tr>'.
                            '<td><a href="'.URL::to("/reports/calendar-approval/".$data['city_id']."".$start.$end."").'">'.$data['city_name'].'</td>'.
                            '<td class="right">'.$created.'</td>'.
                            '<td class="right">'.$approved.'</td>'.
                            '<td class="right">'.round($percent_approved,0,PHP_ROUND_HALF_UP).'%</td>'.
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
            format: 'dd-mm-yyyy',
        });

        $('#end_date').pickadate({
            format: 'dd-mm-yyyy'
        });

        $('#month').pickadate({
            format: 'mmmm, yyyy',
            showMonthsShort: false,
            viewMode: 'months',
            selectYears: true,
            selectMonths: true,

        });

         $('.list_popover').popover({'html' : true});
    });

</script>

@stop

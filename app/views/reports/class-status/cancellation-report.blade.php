@extends('layouts.master')

@section('body')

<link rel="stylesheet" href="{{URL::to('/')}}/css/default.css" id="theme_base">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.date.css" id="theme_date">
<link rel="stylesheet" href="{{URL::to('/')}}/css/default.time.css" id="theme_date">
<link rel="stylesheet" href="{{URL::to('/')}}/css/calendar.css" id="theme_date">
<script src='{{URL::to("/")}}/js/lib/moment.min.js'></script>
<script src='{{URL::to("/")}}/js/fullcalendar.js'></script>
<script type="text/javascript">

 
<script type="text/javascript">
    $(function () {
        $('.footable').footable({
            breakpoints: {

                phone: 555

            },

            filter: {
                filterFunction: function (index) {
                    var $t = $(this),
                        $table = $t.parents('table:first'),
                        filter = $table.data('current-filter').toUpperCase(),
                        tableFilterTextOnly = $table.data('filter-text-only');

                    var text;
                    $t.find('td').each(function () {
                        var $td = $(this);
                        var $th = $table.find('th').eq($td.index());

                        if (!$th.data('filter-ignore')) {
                            text += $td.text();

                            if (!tableFilterTextOnly) {
                                if (!$th.data('filter-text-only')) {
                                    text += $td.data('value');
                                }
                            }
                        }
                    });

                    return text.toUpperCase().indexOf(filter) >= 0;
                }
            }

        });
    });
</script>



<div class="container-fluid">
    <div class="centered">
        <br>

        <h4 class="sub-title">Calendar Summary</h4>
        <div class="row">
            <div style="padding:10px" class="col-md-4 col-sm-12">
                <img  src="{{URL::to('/img/calendar.png')}}"><br/><br/>
                <button class="btn btn-primary" type="button">
                   Classes Scheduled <span class="badge"> {{count($total_classes)}}</span>
                </button>
            </div>
            <div style="padding:10px" class="col-md-4 col-sm-12">
                <h2 class="center white">{{round((count($cancelled_classes)/count($total_classes))*100,2)}}%</h2>
                <p class="center white">Classes Cancelled</p>
            </div>
            <div style="padding:10px" class="col-md-4 col-sm-12">
                <img  src="{{URL::to('/img/calendar.png')}}"><br/><br/>
                <button class="btn btn-primary" type="button">
                    Classes Cancelled <span class="badge"> {{count($cancelled_classes)}}</span>
                </button>
            </div>
            
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-12">
            </div>
        </div>

        <h2 class="sub-title">Class Cancellation Report</h2>
        
        <br>
        <div class="row">
            <div class="center">
       
                <div class="col-md-12 col-sm-12">
                    <form class="form-inline text-center">
                        <label for="filter">Filter :&nbsp;</label>
                        <input type="text" id="filter" data-filter=#filter class="form-control input-sm">
                        <a href="#clear" class="clear-filter" title="clear filter" id="filter-clear">[clear]</a>
                    </form>
                </div>


            <div class="col-md-4 col-sm-12 center">
                <form class="search_parameters text-center" method="post" action="{{{URL::to('/reports/class-cancelled-report')}}}">
                    <select name="reason" class="form-control">
                        <option value="0">--Select Reason--</option>
                        <option value="student_not_available" <?php
                            if(isset($reason)){
                                if($reason=='student_not_available'){
                                    echo 'selected';
                                }
                            }
                        ?>
                        >Student not available</option>
                        <option value="volunteer_not_available" <?php
                            if(isset($reason)){
                                if($reason=='volunteer_not_available'){
                                    echo 'selected';
                                }
                            } 
                        ?>
                        >Volunteer not available</option>
                    </select>
                    &nbsp; &nbsp;
            </div>

            <div class="col-md-4 col-sm-12">
                    <select name="city" class="form-control">
                        <option value="0">--Select City--</option>
                        <?php
                            foreach ($cities as $city){
                                echo '<option value="'.$city->id.
                                '" ';
                                if(isset($city_id)){
                                    if($city->id==$city_id){
                                        echo 'selected';
                                    }
                                }
                                echo' >'.$city->name.'</option>';
                            }
                        ?>
                    </select>
            </div>

            <div class="col-md-4 col-sm-12">
                    <div class='col-md-6 col-sm-12'>
                        <div class="form-group">
                            <div class="form-group">
                                <input type="text" id='start_date' name="start_date" class="form-control" placeholder="Start Date (From)"
                                    <?php
                                        if(isset($start_date)){
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
                                        if(isset($end_date)){
                                            echo 'value="'.$end_date.'"';
                                        }
                                    ?>
                                >
                            </div>
                        </div>
                    </div>

                    <br/>
                    
            </div>

            <div class="col-md-12 col-sm-12">
                    <input type="submit" value="Filter Results" />
                </form>    
            <br><br/><br/>
                @if(!empty($cancelled_classes))
                <table data-filter="#filter" class="white footable table table-bordered table-responsive toggle-medium" data-filter-timeout="500" data-filter-text-only="true" data-filter-minimum="3">
                    <thead >
                    <tr>
                        <th style="text-decoration:underline">Student Name</th>
                        <th style="text-decoration:underline">Class Type</th>
                        <th data-hide="phone" style="text-decoration:underline">Center</th>
                        <th data-hide="phone" style="text-decoration:underline">City</th>
                        <th style="text-decoration:underline">Class Time</th>
                        <th data-hide="" style="text-decoration:underline" data-filter-ignore="true">Cancelled on</th>
                        <th data-hide="all">Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;
                        foreach ($cancelled_classes as $class) {
                            echo '<tr>'.
                            '<td>'.$class->student_name.'</td>'.
                            '<td>'.ucwords(str_replace('_',' ',$class->event_type)).'</td>'.
                            '<td>'.$class->center_name.'</td>'.
                            '<td>'.$class->city_name.'</td>'.
                            '<td>'.$class->start_time.'</td>'.
                            '<td>'.$class->cancelled_time.'</td>'.
                            '<td>'.ucfirst(str_replace('_', ' ', $class->reason)).', '.$class->comment.'</td>'.
                            '</tr>';
                            $i++;
                        }
                    ?>
                    
                        
                    </tbody>
                </table>
                @else
                <div class="alert alert-warning" role="alert">No Class Cancelled for Selected Filters</div>
                @endif

            </div>
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



    $(function () {


        $('.clear-filter').click(function (e) {
            e.preventDefault();
            $('table').trigger('footable_clear_filter');
        });


        /*$(function () {
            $('#datetimepicker6').datepicker();
            $('#datetimepicker7').datepicker({
                useCurrent: false //Important! See issue #1075
            });
            $("#datetimepicker6").on("dp.change", function (e) {
                $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
            });
            $("#datetimepicker7").on("dp.change", function (e) {
                $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
            });
        });*/

    });


</script>

@stop
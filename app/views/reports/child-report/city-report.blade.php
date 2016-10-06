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

        <h2 class="sub-title">City Propeller Data</h2>
        <br>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form class="search_parameters" method="post" action="{{{URL::to('/reports/child-report/city-report')}}}">
                    <p class="white">Select City &amp; Center</p>

                <div class="col-md-6">
                    <select name="city" class="form-control">
                        <?php
                            foreach ($cities as $city){
                                echo '<option value="'.$city->id.
                                '" '.($city->id==$city_id?"selected":"").' >'.$city->name.'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 ">
                    <select name="centers" class="form-control">
                        <option value="0">--Select Center--</option>
                        <?php
                            foreach ($centers as $center){
                                echo '<option value="'.$center->id.
                                '"  '.($center->id==$center_id?"selected":"").'>'.$center->name.'</option>';

                                /*($city->id==$city_id?"selected":"")*/
                            }
                        ?>
                    </select>
                </div><br/><br/>
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
                <br/><br/>
                    <input type="submit" value="Filter Values" />
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2     white">

            <br>
                @if(count($child_data)!=0)
                <table class="white footable table table-bordered table-responsive toggle-medium" data-filter-timeout="500" data-filter-text-only="true" data-filter-minimum="3">
                    <thead >
                    <tr>
                        <th width="20%" style="text-decoration:underline">Propeller Name</th>
                        <th width="20%" style="text-decoration:underline">Wingman Name</th>
                        <th width="20%" style="text-decoration:underline">Center</th>
                        <th width="10%" data-hide="phone" data-sort-initial="true" style="text-decoration:underline">Journals Filled</th>
                        <th width="10%" data-hide="phone" data-sort-initial="true" style="text-decoration:underline">Wingman Sessions Scheduled</th>
                        <th width="10%" data-hide="phone" data-sort-initial="true" style="text-decoration:underline">ASV Sessions Scheduled</th>
                        <th width="10%" data-hide="phone" data-sort-initial="true" style="text-decoration:underline">Wingman Modules Covered</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;
                        foreach ($child_data as $child) {
                            echo '<tr>'.
                            '<td><a href="'.URL::to('profile/'.$child->id).'">'.$child->name.'</td>'.
                            '<td>'.$child->wingman_name.'</td>'.
                            '<td>'.$child->center_name.'</td>'.
                            '<td>'.$child->journal_count.'</td>'.
                            '<td>'.$child->wingman_session_count.'</td>'.
                            '<td>'.$child->asv_session_count.'</td>'.
                            '<td><a href="'.URL::to('modules/'.$child->id).'">'.$child->wingman_module_attended.'</a></td>'.
                            '</tr>';
                            $i++;
                        }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr class="text-center">
                            <td valign="center" colspan='3' rowspan="2" class="center" ><strong>Total</strong></td>
                            <td data-hide="phone" ><strong>{{$total['journal_count']}}</strong></td>
                            <td data-hide="phone" ><strong>{{$total['wingman_session_count']}}</strong></td>
                            <td data-hide="phone" ><strong>{{$total['asv_session_count']}}</strong></td>
                        </tr>
                        <tr>
                            <td data-hide="phone"><strong>Total Classes</strong></td>
                            <td data-hide="phone" colspan="2" class="text-center">
                                <strong>{{$total['wingman_session_count']+$total['asv_session_count']}}</strong>
                            </td>
                        </tr>
                    </tfoot>

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

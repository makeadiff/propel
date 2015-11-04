@extends('layouts.master')

@section('body')

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

        <h2 class="sub-title">Class Cancellation Report</h2>
        
        <h4 class="white">(Classes cancelled Nationally: {{count($cancelled_classes)}}/{{count($total_classes)}})</h4>

        <br>
        <div class="row">
            <!--<div class="col-md-12">

                @foreach($cities as $city)
                    <div style="padding:10px" class="col-md-3 col-sm-8">
                        <a href="{{URL::to('/')}}/reports/class-status/city/{{$city->id}}" class="btn btn-primary btn-dash transparent"><img  src="{{URL::to('/img/cities.png')}}"><br/>{{$city->name}}</a><br>
                    </div>
                @endforeach

            </div>-->
            <div class="col-md-12 col-sm-12">
                <form class="form-inline text-center">
                    <label for="filter">Filter :&nbsp;</label>
                    <input type="text" id="filter" data-filter=#filter class="form-control input-sm">
                    <a href="#clear" class="clear-filter" title="clear filter" id="filter-clear">[clear]</a>
                </form>

                <form class="search_parameters">
                    <label for="reason">Reason: </label>
                    <select name="reason" class="">
                        <option value="">--Select Reason--</option>
                        <option value="student_not_available">Student not available</option>
                        <option value="volunteer_not_available">Volunteer not available</option>
                    </select>
                    &nbsp; &nbsp;

                    <label for="reason">City: </label>
                    <select name="city">
                        <option value="">--Select City--</option>
                        <?php
                            foreach ($cities as $city){
                                echo '<option value="'.$city->id.
                                '">'.$city->name.'</option>';
                            }
                        ?>
                    </select>
                    <br/>
                    <input type="submit" value="Filter Results" />
                </form>


            <br>
                <table data-filter="#filter" class="white footable table table-bordered table-responsive toggle-medium" data-filter-timeout="500" data-filter-text-only="true" data-filter-minimum="3">
                    <thead >
                    <tr>
                        <th style="text-decoration:underline">Student Name</th>
                        <th data-sort-initial="true" style="text-decoration:underline">Wingman Name</th>
                        <th data-hide="phone" style="text-decoration:underline">Center</th>
                        <th data-hide="phone" style="text-decoration:underline">City</th>
                        <th style="text-decoration:underline">Class Time</th>
                        <th data-hide="" style="text-decoration:underline" data-filter-ignore="true">Cancelled on</th>
                        <th data-hide="all">Reason</th>
                    </tr>
                    </thead>
                    <?php
                        foreach ($cancelled_classes as $class) {
                            echo '<tr>'.
                            '<td>'.$class->student_name.'</td>'.
                            '<td>'.$class->wingman_name.'</td>'.
                            '<td>'.$class->center_name.'</td>'.
                            '<td>'.$class->city_name.'</td>'.
                            '<td>'.$class->start_time.'</td>'.
                            '<td>'.$class->cancelled_time.'</td>'.
                            '<td>'.ucfirst(str_replace('_', ' ', $class->reason)).', '.$class->comment.'</td>'.
                            '</tr>';

                        }
                    ?>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            <br>
        </div>
    </div>
</div>

@stop
@extends('layouts.master')

@section('body')

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
                    <select name="city">
                        <?php
                            foreach ($cities as $city){
                                echo '<option value="'.$city->id.
                                '" '.($city->id==$city_id?"selected":"").' >'.$city->name.'</option>';
                            }
                        ?>
                    </select>

                    <select name="centers">
                        <option value="0">--Select Center--</option>
                        <?php
                            foreach ($centers as $center){
                                echo '<option value="'.$center->id.
                                '"  '.($center->id==$center_id?"selected":"").'>'.$center->name.'</option>';

                                /*($city->id==$city_id?"selected":"")*/
                            }
                        ?>
                    </select>
                    <input type="submit" value="Filer Values`" />
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3 white">

            <br>
                @if(count($child_data)!=0)
                <table class="white footable table table-bordered table-responsive toggle-medium" data-filter-timeout="500" data-filter-text-only="true" data-filter-minimum="3">
                    <thead >
                    <tr>
                        <th style="text-decoration:underline">Propeller Name</th>
                        <th data-sort-initial="true" style="text-decoration:underline">Wingman Name</th>
                        <th data-hide="phone" style="text-decoration:underline">Center</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($child_data as $child) {
                            echo '<tr>'.
                            '<td>'.$child->name.'</td>'.
                            '<td>'.$child->wingman_name.'</td>'.
                            '<td>'.$child->center_name.'</td>'.
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

@stop
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
        <br>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form class="search_parameters" method="post" action="{{{URL::to('/reports/child-report/city-report')}}}">
                    <p class="white">Select City &amp; Center</p>
                        
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
                    <input type="submit" value="Filer Values`" />
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
                        <th width="20%" style="text-decoration:underline">Events Created</th>
                        <th width="20%" style="text-decoration:underline">Events Approved</th>
                        <th width="20%" style="text-decoration:underline">% Events Approved</th>
                        
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
                            $percent_approved = round((float)($approved/$created * 100),2);

                            echo '<tr>'.
                            '<td><a href="">'.$data['city_name'].'</td>'.
                            '<td>'.$created.'</td>'.
                            '<td>'.$approved.'</td>'.
                            '<td>'.$percent_approved.'</td>'.
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
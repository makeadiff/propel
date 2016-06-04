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

        <h3 class="sub-title">Wingman Modules Covered - {{ucwords(strtolower($child->name))}}</h3>
        <br>    
        
        <div class="row">
            <div class="col-md-8 col-md-offset-2     white">

            <br>
                @if(count($wingmanModules)!=0)
                <table class="white footable table table-bordered table-responsive toggle-medium" data-filter-timeout="500" data-filter-text-only="true" data-filter-minimum="3">
                    <thead >
                    <tr>
                        <th width="60%" style="text-decoration:underline">Module Name</th>
                        <th width="40%" style="text-decoration:underline">Date (Conducted)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;
                        foreach ($wingmanModules as $modules) {
                            echo '<tr>'.
                            '<td>'.$modules->name.'</td>'.
                            '<td>'.date('d-m-y',strtotime($modules->time)).'</td>'.
                            '</tr>';
                            $i++;
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
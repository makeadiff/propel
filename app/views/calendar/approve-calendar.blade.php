@extends('layouts.master')


@section('body')

<div class="container-fluid">


    <div class="row">
        <h2 class="sub-title">Approve Calendar</h2>
        <p style="text-align:center; color:#FFF">(Pending Approval) <br/>Select mutliple wingmen to approve multiple calendars at once.</p>
        <br>

        <div class="col-md-offset-2 col-md-8">
            <div class="alert alert-danger" id="errorMessageApproval" style="display:none;" role="alert"></div>
            <p class="white"><input type="checkbox" onclick="select_all()" id="select_all">&nbsp;Select All<br/></p>
            <form id="approve-calendar" role="form" method="post" enctype="multipart/form-data" action="{{{URL::to('/calendar/bulk-approve/')}}}" onsubmit="return validate_calendar_approval()">
            <table class="table table-bordered table-responsive white footable">
                <thead>
                <tr>
                    <th width ="5%" data-sort-ignore="true">
                    
                    </th>
                    <th width="30%" >
                        Wingman Name
                    </th>
                    <th width="30%" data-hide="phone">
                        Child Name
                    </th>
                    <th width="20%" data-hide="phone">
                        Month
                    </th>
                    <th width="15%" data-sort-ignore="true">
                        Action
                    </th>
                </tr>
                </thead>
                
                <tbody>
                    <?php 
                    $lastMonth = 0;
                    $lastYear = 0;
                    $student_id = 0;
                    foreach($datalist as $data){
                        $month = date("n",strtotime($data->month));
                        $year = date("Y",strtotime($data->month));
                        if(!($month == $lastMonth && $year == $lastYear && $data->student_id == $student_id)){
                            echo '<tr>';

                                echo '<td>'
                                .'<input type="checkbox" class="check_calendar" value="'.$data->student_id.'/'.$month.'/'.$year.'" name="submit_value[]">'
                                .'</td>'
                                .'<td>'
                                .'<a class="white" href="'.URL::to('/calendar/'.$data->wingman_id).'">'.ucwords(strtolower($data->wingman_name)).'</a>'
                                .'</td>'
                                .'<td>'.ucwords(strtolower($data->student_name)).'</td>'
                                .'<td><a target="_blank" href="'.URL::to('calendar/'.$data->wingman_id.'/'.$data->student_id).'?date='.date("Y",strtotime($data->month)).'-'.date("m",strtotime($data->month)).'-'.date("d",strtotime($data->month)).'">'.date("M",strtotime($data->month)).', '.date("Y",strtotime($data->month)).'</a></td>'
                                .'<td>'
                                .'<a class="btn btn-default" href="'.URL::to('/calendar/approve/'.$data->student_id.'/'.$month.'/'.$year).'">Approve</a>'
                                .'</td>'
                            .'</tr>';
                            $lastMonth = $month;
                            $lastYear = $year;
                            $student_id = $data->student_id;
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="col-md-offset-2 col-md-8" style="text-align:center;">
                <button type="submit" class="btn btn-default">Approve Selected</button>
            </div>
            </form>
        </div>
        
    </div>

</div>




@stop

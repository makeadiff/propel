@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Assign Wingmen</h2>
        <br>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
            @if(count($selected_wingmen)!=0)
            <p style="text-align:center; color:#FFF; font-size:16px">Wingmen assigned to you.</p>
            <table class="table table-bordered table-responsive white footable" width="50%">
                <thead>
                <tr>
                    <th data-sort-ignore="true">
                        Wingman Name
                    </th>
                </tr>
                </thead>
                <tbody>
                   @foreach($selected_wingmen as $wingman)
                        <tr>
                            <td>{{ucwords(strtolower($wingman->name))}}</td>
                        </tr>
                   @endforeach
                </tbody>
                <tfoot>
                <tr  class="hide-if-no-paging">
                    <td colspan="7">
                        <div class="text-center">
                            <ul class="pagination pagination-centered"></ul>
                        </div>
                    </td>
                </tr>
                </tfoot>

            </table>
            <button type="button" id="assignStudents" class="btn btn-primary">Edit Assignment</button>
            @else
            <p style="text-align:center; color:#FFF">No wingman assigned to you.</p>
            <button type="button" class="btn btn-primary" id="assignStudents">Assign Wingman</button>
            @endif
            </div>
        <br>

        <br>
        </div>
    </div>
</div>

<div class="modal fade" id="assignStudentsModal" tabindex="-1" role="dialog" aria-labelledby="assignStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add/Edit Wingman</h4>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{{URL::to('/settings/wingmen')}}}">
            <div class="modal-body">
                    <div class="form-group">

                    </div>
                    <div class="form-group">
                        <label>Wingmen:</label><br/>
                        <div class="selectBox" style="width=100%; height:180px; padding:10px; overflow-y:scroll; border:thin #CCC solid;">
                            <?php
                                $i = 0;
                                foreach($all_wingmen as $wingman){
                                    echo '<input name="wingmen[]" type="checkbox" id="group'.$i.'" value="'.$wingman->id.'" '.(is_numeric($wingman->phone)?'':$wingman->phone).'/>
                                    <label for="group'.$i.'">'.ucwords(strtolower($wingman->name)).'</label><br/>';
                                $i++;
                                }
                            ?>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                
            </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="{{{URL::to('/')}}}/js/section/select-students.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#assignStudents').click(function(){
            $('#assignStudentsModal').modal('show');
        });
    });        
</script>
@stop




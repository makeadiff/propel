@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Wingman Journal</h2>
        <br>


        <div class="row">
            <div class="col-md-offset-2 col-md-8">
            <table class="table table-bordered table-responsive white footable">
                <thead>
                <tr>
                    <th>
                        Type
                    </th>
                    <th>
                        Title
                    </th>
                    <th data-sort-ignore="true">
                        Date
                    </th>
                    <th data-sort-ignore="true">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                        <td>
                            <?php
                                $type = $entry->type;
                                if($type=="child_feedback"){
                                    echo "Child Feedback";
                                }
                                else if($type=="module_feedback"){
                                    echo "Module Feedback";
                                }
                                else{
                                    echo "Other";
                                }
                            ?>
                        </td>
                        <td><a class="white" href="../journal-entry/{{{$entry->id}}}">{{{$entry->title}}}</a></td>
                        <td>{{{date_format(date_create($entry->on_date),'l, jS F Y')}}}</td>
                        <td><a href="{{{URL::to('/journal-entry/' . $entry->id . '/edit')}}}" ><span class="glyphicon glyphicon-edit white"></span> </a>&nbsp; &nbsp;
                            <a href="javascript:checkDelete({{{$entry->id}}})"><span class="glyphicon glyphicon-remove white"></span></a>
                        </td>
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
            <br>
            <a href="../journal-entry/create" class="btn btn-primary">New Journal Entry</a>
        </div>
        <br>

        <br>
        </div>
    </div>
</div>

<script>
    function checkDelete(id) {
        if (confirm('Really delete?')) {
                $.ajax({
                type: "DELETE",
                url: '{{{URL::to('/')}}}' + '/journal-entry/' + id,
                complete: function(result) {
                    location.reload(true);
            }
            });

        }
    }
</script>
@stop

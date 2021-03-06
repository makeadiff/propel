@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Wingman Journal</h2>
        <br>

        <div class="row">
            <div class="col-md-offset-2 col-md-8">

            @if($user_group == 'Propel Wingman' || $user_group == 'Aftercare Wingman' || $user_group == 'Propel Fellow')
                <a href="../journal-entry/create" class="btn btn-primary">+ New Journal Entry</a>
            @endif
            <br/><br/>
            @if(count($entries)!=0)
            <table class="table table-bordered table-responsive white footable data-paging="true" data-page-size="12"">
                <thead>
                <tr>
                    <th width="25%">
                        Type
                    </th>
                    <th width="45%">
                        Title
                    </th>
                    <th width="20%">
                        Date
                    </th>
                    <th data-sort-ignore="true" width="10%" class="center">
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
                                    echo "Youth Feedback";
                                }
                                else if($type=="module_feedback"){
                                    echo "Module Feedback";
                                }
                                else{
                                    echo "Other";
                                }
                            ?>
                        </td>
                        <td><a class="white" href="../journal-entry/{{$entry->id}}">{{$entry->title}}</a></td>
                        <td class="right" data-value={{date_format(date_create($entry->on_date),'U')}}>{{date_format(date_create($entry->on_date),'j M y')}}</td>
                        <td class="center"><a href="{{URL::to('/journal-entry/' . $entry->id . '/edit')}}" ><span class="glyphicon glyphicon-edit white"></span> </a>&nbsp; &nbsp;
                            <a href="javascript:checkDelete({{$entry->id}})"><span class="glyphicon glyphicon-remove white"></span></a>
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
            @else
            <p style="text-align:center; color:#FFF">No journal entry for the selected wingman.{{$user_group}}</p>
            @endif

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
                url: '{{URL::to('/')}}' + '/journal-entry/' + id,
                complete: function(result) {
                    location.reload(true);
            }
            });

        }
    }
</script>
@stop

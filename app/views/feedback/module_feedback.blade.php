@extends('layouts.master')

@section('body')

<div class="container-fluid">
    <div class="centered">
        <br>

        <h2 class="sub-title">Module Feedback</h2>
        <br>

        <div class="centered" style="text-align:center">
            <select id="moduleId" class="form-control" placeholder="Student" name="student" style="width: auto; margin:auto" onchange="toggleDisplay();"> 
                <option value="A" selected="selected">-- Select Module --</option>
            @foreach($modules as $module)
                <option value="{{$module->id}}">{{$module->name}}</option>
            @endforeach
            </select>
            <br/>
        </div>

        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                
            @if(count($entries)!=0)
            <table class="table table-bordered table-responsive white footable">
                <thead>
                <tr>
                    <th>
                        Module Name
                    </th>
                    <th>
                        Wingman Name
                    </th>
                    <th >
                        City
                    </th>
                    <th >
                        Date
                    </th>
                </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr class="tableRows {{$entry->module_id}}">
                        <td><a class="white" href="../journal-entry/{{$entry->id}}">{{$entry->title}}</a></td>
                        <td>{{$entry->wingman_name}}</td>
                        <td>{{$entry->city_name}}</td>
                        <td data-value={{date_format(date_create($entry->on_date),'U')}}>{{date_format(date_create($entry->on_date),'l, jS F Y')}}</td>
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
            <p style="text-align:center; color:#FFF">No feedback entry for the selected child.</p>
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

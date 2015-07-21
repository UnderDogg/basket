<div class="btn-group pull-right">
    @if(isset($edit))
        <a href="{{Request::segment(0)}}/{{Request::segment(1)}}/{{$id}}/edit" class="btn btn-default">
            <span class="glyphicon glyphicon-edit"></span> Edit
        </a>
    @endif
    @if(isset($sync))
        <a class="btn btn-default">
            <span class="glyphicon glyphicon-refresh"></span> Sync
        </a>
    @endif
    @if(isset($delete))
        <a href="{{Request::segment(0)}}/{{Request::segment(1)}}/{{$id}}/delete" class="btn btn-danger">
            <span class="glyphicon glyphicon-remove-circle"></span> Delete
        </a>
    @endif
</div>


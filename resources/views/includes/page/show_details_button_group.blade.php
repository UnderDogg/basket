<div class="btn-group pull-right">
    @if(isset($edit))
        <a href="{{Request::segment(0)}}/{{Request::segment(1)}}/{{$id}}/edit" class="btn btn-default">
            <span class="glyphicon glyphicon-edit"></span> Edit
        </a>
    @endif
    @if(isset($locations))
        <a href="{{Request::segment(0)}}/{{Request::segment(1)}}/{{$id}}/locations" class="btn btn-default">
            <span class="glyphicon glyphicon-map-marker"></span> Locations
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
    @if(isset($fulfil))
        <a href="{{Request::segment(0)}}/{{Request::segment(1)}}/{{$id}}/fulfil" class="btn btn-info{{ $fulfil == true ? ' ' : ' disabled' }}">
            <span class="glyphicon glyphicon-gift"></span> Fulfil
        </a>
    @endif
    @if(isset($cancel))
        <a href="{{Request::segment(0)}}/{{Request::segment(1)}}/{{$id}}/request-cancellation" class="btn btn-danger{{ $cancel == true ? ' ' : ' disabled' }}">
            <span class="glyphicon glyphicon-remove-circle"></span> Request Cancellation
        </a>
    @endif
    @if(isset($ip))
            <a href="{{Request::segment(0)}}/{{Request::segment(1)}}/{{$id}}/ips" class="btn btn-default">
                <span class="glyphicon glyphicon-list-alt"></span> View IP's
            </a>
    @endif
</div>


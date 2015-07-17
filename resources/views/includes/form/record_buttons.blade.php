<div class="btn-group">
    <a href="{{ url('/'.$crudName, $record->id) }}" type="button" class="btn btn-default btn-xs"> VIEW </a>
    <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="{{ url('/'.$crudName.'/'.$record->id.'/edit') }}">Edit</a></li>
        {{--<li><a href="#"> NEW BUTTON SPACER </a></li>--}}
        <li role="separator" class="divider"></li>
        {{-- */ $crudNameUc=ucwords($crudName); /* --}}
        {!! Form::open( ['method'=>'delete','action'=>[ $crudNameUc.'Controller@destroy', $record->id ] ] ) !!}
        <button name={{ 'delete'.$record->id }} type="submit" class="btn btn-xs dropdown-delete">
            <li><a>Delete</a></li>
        </button>
        {!! Form::close() !!}
    </ul>
</div>

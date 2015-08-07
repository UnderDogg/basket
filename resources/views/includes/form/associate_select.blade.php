@if(isset($associateField)){{-- */$itemProperty=$associateField;/* --}} @else {{-- */$itemProperty=$field;/* --}}@endif
@if($object->count())
    @foreach($object as $item)
        {{-- */ if($item->{$associate} !== null) $selects[$item->{$associate}->id]=$item->{$associate}->{$itemProperty};/* --}}
    @endforeach
@endif

<select class="filter form-control" name="{{ $field }}">
    <option value="">All</option>
    @if($object->count())
        @foreach(array_unique($selects) as $key => $option)
            <option
            @if(Request::only($field)[$field] == $key)
                selected="selected"
                @endif
                value="{{ $key }}">{{ $option }}</option>
        @endforeach
    @else()
        <option selected="selected" name="{{ Request::only($field)[$field] }}">
            {{ Request::only($field)[$field] }}
        </option>
    @endif
</select>

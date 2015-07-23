@if($object->count())
    @foreach($object as $item)
        {{-- */$selects[]=$item->{$field};/* --}}
    @endforeach
@endif

<select class="filter form-control" name="{{ $field }}" value="{!! Request::only($field)[$field] !!}">
    <option value="">All</option>
    @if($object->count())
        @foreach(array_unique($selects) as $option)
            <option
                @if(Request::only($field)[$field] == $option)
                    selected="selected"
                @endif
                    name="{{ $option }}">{{ ucwords($option) }}</option>
        @endforeach
    @else()
        <option selected="selected" name="{!! Request::only($field)[$field] !!}">
            {!! Request::only($field)[$field] !!}
        </option>
    @endif
</select>

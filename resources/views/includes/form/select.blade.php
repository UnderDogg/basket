<br>
<hr class="hr-tight">
@if($object->count())
    @foreach($object as $item)
        {{-- */$selects[]=$item->{$field};/* --}}
    @endforeach
@endif

<select class="filter form-control" name="{{ $field }}" value="{!! Request::only($field)[$field] !!}">
    <option></option>
    @if($object->count())
        @foreach(array_unique($selects) as $option)
            <option
                @if(Request::only($field)[$field] == $option)
                    selected="selected"
                @endif
                    name="{{ $option }}">{{ $option }}</option>
        @endforeach
    @else()
        <option selected="selected" name="{!! Request::only($field)[$field] !!}">{!! Request::only($field)[$field] !!}</option>
    @endif
</select>

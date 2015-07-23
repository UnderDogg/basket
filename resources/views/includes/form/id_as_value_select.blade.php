{{--
$object     The object containing Id and Name fields of items to populate select
$field      The input field name to update with ID of selected object
$select_id  The ID of the item that should be auto selected
--}}
@if(isset($object))
    <select class="filter form-control" name="{{$field}}" value="{!! Request::only($field)[$field] !!}">
        @if($object->count())
            @foreach($object as $item)
                <option
                @if($select_id === $item->id)
                    selected="selected"
                @endif
                    value="{{ $item->id }}">{{ ucwords($item->name) }}</option>
            @endforeach
        @else()
            <option selected="selected" name="{!! Request::only($field)[$field] !!}">
                {!! Request::only($field)[$field] !!}
            </option>
        @endif
    </select>
@endif
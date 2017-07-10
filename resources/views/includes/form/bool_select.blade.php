@if($object->count())
    @foreach($object as $item)
        @php $selects[]=$item->{$field}; @endphp
    @endforeach
@endif

<select class="filter form-control" name="{{ $field }}">
    <option value="">All</option>
    @if($object->count())
        @foreach(array_unique($selects) as $option)
            <option
            @if((int) Request::only($field)[$field] === (int) $option && isset(Request::only($field)[$field]))
                selected="selected"
            @endif
                name="{{ $option }}"
                value="{{ $option }}">
                @if($option == 0)
                    {{ $false }}
                @elseif($option == 1)
                    {{ $true }}
                @endif
            </option>
        @endforeach
    @else()
        <option selected="selected" name="{{ Request::only($field)[$field] }}">
            {{ Request::only($field)[$field] }}
        </option>
    @endif
</select>

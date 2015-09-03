@forelse($object as $item)
    {{-- */$selects[strtolower($item->{$field})]=ucwords($item->{$field});/* --}}
@empty
    {{-- */$selects = [];/* --}}
@endforelse
{!! Form::select($field, $selects, Request::only($field)[$field], ['class' => 'filter form-control']) !!}

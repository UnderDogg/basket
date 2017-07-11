@forelse($object as $item)
    @php $selects[strtolower($item->{$field})]=ucwords($item->{$field}); @endphp
@empty
    @php $selects = []; @endphp
@endforelse
{!! Form::select($field, $selects, Request::only($field)[$field], ['class' => 'filter form-control']) !!}

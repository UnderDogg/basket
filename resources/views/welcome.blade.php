@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>DASHBOARD</h1>
    @if(count($user->locations) > 0)
        <h2>Make Application</h2>
        <ul>
            @foreach($user->location as $location)
                <li><strong><a href="/locations/{{ $location->id }}/applications/make">{{ $location->name }}</a></strong> <em>{{ $location->address }}</em></li>
            @endforeach
        </ul>
    @endif

@endsection

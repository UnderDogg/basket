@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])
    <div class="container">
        <h1>DASHBOARD</h1>
        @if(count($user->locations) > 0)
            <h2>Make Application</h2>
            <hr/>
            <ul>
                @foreach($user->locations as $location)
                    <li><strong><a href="/locations/{{ $location->id }}/applications/make">{{ $location->name }}</a></strong> <em>{{ $location->address }}</em></li>
                @endforeach
            </ul>
        @endif
        <hr/>
        <h2>Support Details</h2>
        <div class="well well-sm">
            <h4>Customer Enquiries</h4>
            <ul>
                <li><strong>Tel:</strong> 03333 444 224</li>
                <li><strong>Email:</strong> hello@paybreak.com</li>
            </ul>
        </div>
        <div class="well well-sm">
            <h4>Useful Links</h4>
            <ul>
                <li><a href="http://www.afforditnow.com/retailer/retailer-developer-support/" target="_blank">Retailer Developer Support</a>
                    <ul>
                        <li><a href="http://paybreak.github.io/retailer-integration-guide/" target="_blank">Integration Guide</a></li>
                        <li><a href="http://www.afforditnow.com/wp-content/uploads/2015/02/afforditnow_images_v1.zip">Download</a> Promotional Assets (.zip file)</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

@endsection

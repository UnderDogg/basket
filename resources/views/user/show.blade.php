@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>
        {{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        @include('includes.page.show_details_button_group', ['id'=>$user->id,'edit'=>true,'delete'=>true])
    </h2>
    @include('includes.page.breadcrumb', ['override2'=>$user->name])

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="tabbutton active"><a href="#fragment-1"><h5>User Details</h5></a></li>
        </ul>
        <div class="col-xs-12">&nbsp;</div>
        <hr>
        {{--FIRST PANEL: USER DETAILS--}}

        <div id="fragment-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Key information</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Name: </strong> {{ $user->name  }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email: </strong> {{ $user->email  }}
                        </li>
                        <li class="list-group-item">
                            @if($user->merchant !== null)
                                <strong>Merchant: </strong>
                                <a href="{{Request::segment(0)}}/merchants/{{$user->merchant->id}}">
                                    {{ $user->merchant->name }}
                                </a>
                            @endif
                        </li>
                        <li class="list-group-item">
                            @if($user->locations !== null)
                                <strong>Locations: </strong><br />
                                @foreach ($user->locations as $location)
                                    {{ $location->name }}<br />
                                @endforeach
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

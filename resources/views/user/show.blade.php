@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}</h2>
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
                    @if($user !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Reference: </strong> {{ $user->name  }}
                            </li>
                            <li class="list-group-item">
                                <strong>Name: </strong> {{ $user->email  }}
                            </li>
                            <li class="list-group-item">
                                <strong>Merchant: </strong>
                                <a href="{{Request::segment(0)}}/merchants/{{$user->merchant->id}}">
                                    {{ $user->merchant->name }}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <strong>Locations: </strong> {{ 'To Be Defined'  }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

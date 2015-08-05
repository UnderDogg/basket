@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        @include('includes.page.show_details_button_group', ['id'=>$location->id,'edit'=>true,'sync'=>true,'delete'=>true])
    </h2>
    @include('includes.page.breadcrumb', ['override2'=>$location->name])

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="tabbutton active"><a href="#fragment-1"><h5>Location Details</h5></a></li>
        </ul>
        <div class="col-xs-12">&nbsp;</div>
        <hr>
        {{--FIRST PANEL: LOCATION DETAILS--}}

        <div id="fragment-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Key information</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Reference: </strong> {{ $location->reference  }}
                        </li>
                        <li class="list-group-item">
                            <strong>Name: </strong> {{ $location->name  }}
                        </li>
                        <li class="list-group-item">
                            <strong>Active Status: </strong>
                            @if( $location->active == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i></span>
                            @elseif( $location->active == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i></span>
                            @endif
                        </li>
                        <li class="list-group-item">
                            @if($location->installation !== null)
                                <strong>Installation: </strong>
                                <a href="{{Request::segment(0)}}/installations/{{$location->installation->id}}">
                                    {{ $location->installation->name }}
                                </a>
                            @endif
                        </li>
                        <li class="list-group-item">
                            <strong>Location Email Address: </strong> {{ $location->email  }}
                        </li>
                        <li class="list-group-item">
                            <strong>Location Address: </strong> {{ $location->address  }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

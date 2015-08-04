@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . Request::segment(1)) }}
        @include('includes.page.show_details_button_group', ['id'=>$role->id,'edit'=>true,'sync'=>true,'delete'=>true])
    </h2>
    @include('includes.page.breadcrumb')

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="tabbutton active"><a href="#fragment-1"><h5>Role Details</h5></a></li>
            <li role="presentation" class="tabbutton"><a href="#fragment-2"><h5>Active Permissions</h5></a></li>
        </ul>
        <div class="col-xs-12">&nbsp;</div>
        <hr>
        {{--FIRST PANEL: ROLE DETAILS--}}

        <div id="fragment-1">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Key information</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Role ID: </strong> {{ $role->id  }}
                            </li>
                            <li class="list-group-item">
                                <strong>Role Code: </strong> {{ $role->name  }}
                            </li>
                            <li class="list-group-item">
                                <strong>Display Name: </strong> {{ $role->display_name  }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Description</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li style="min-height: 127px" class="list-group-item">
                                {{ $role->description  }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{--SECOND PANEL: ACTIVE PERMISSIONS--}}

        <div id="fragment-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Permissions Of Role</h4></div>
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th class="hidden-xs hidden-sm">Permission Code</th>
                        <th>Display Name</th>
                        <th class="hidden-xs hidden-sm">Description</th>
                    </tr>
                    @if($role->permissions !== null)
                        @foreach ($role->permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td class="hidden-xs hidden-sm">{{ $permission->name }}</td>
                                <td>{{ $permission->display_name }}</td>
                                <td class="hidden-xs hidden-sm">{{ str_limit($permission->description, 60) }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>

@endsection

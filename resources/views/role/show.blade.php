@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . Request::segment(1)) }}
        @include('includes.page.show_details_button_group', ['id'=>$role->id,'edit'=>true,'delete'=>true])
    </h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#part1">Role Details</a></li>
            <li><a data-toggle="tab" href="#part2">Active Permissions</a></li>
        </ul>
        <br/>
        <div class="tab-content">
            <div id="part1" class="tab-pane fade in active">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Key Information</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Role Id</dt>
                            <dd>{!! $role->id !!}</dd>
                            <dt>Role Code</dt>
                            <dd>{!! $role->name !!}</dd>
                            <dt>Display Name</dt>
                            <dd>{!! $role->display_name !!}</dd>
                            <dt>Description</dt>
                            <dd>{!! $role->description !!}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div id="part2" class="tab-pane fade">
                <div class="panel panel-default">
                    <table class="table table-striped table-bordered">
                        <tr><th colspan=12 style="padding-left: 16px;">Permissions of role</th></tr>
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

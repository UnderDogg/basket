@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' edit ' . Request::segment(1)) }}</h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])

    {!! Form::model($role, ['method' => 'PATCH', 'action' => ['RolesController@update', $role->id], 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Role Details</strong></div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('display_name', 'Display Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-6">
            <div style="height: 100%;" class="panel panel-default">
                <div class="panel-heading"><strong>Role Permissions</strong></div>
                <div class="panel-body panel-tight-space">
                    <div style="padding-right:0px;" class="col-xs-6">
                        <div class="panel rolePanel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Applied Permissions</h3>
                                <hr class="hr-tight">
                            </div>
                            <div class="panel-body panel-tight-space">
                                <div style="height: 340px;" id="permissionsAppliedHolder" class="connectedSortable col-xs-12">
                                    @if($role->permissions !== null)
                                        @foreach ($role->permissions as $permission)
                                            <div name="{{ $permission->id }}" class="draggableItem">{{ $permission->display_name }}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding-left:0px;" class="col-xs-6">
                        <div class="panel rolePanel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Permissions Available</h3>
                                <hr class="hr-tight">
                            </div>
                            <div class="panel-body panel-tight-space">
                                <div style="height: 340px;" id="permissionsAvailableHolder" class="connectedSortable col-xs-12">
                                    @foreach ($permissionsAvailable as $permission)
                                        <div name="{{ $permission->id }}" class="draggableItem">{{ $permission->display_name }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>
    </div>

    <input id="permissionsApplied" name="permissionsApplied" type="hidden" value="@foreach ($role->permissions as $permission){{ ':'.$permission->id  }}@endforeach">
    <input id="permissionsAvailable" name="permissionsAvailable" type="hidden" value="">
    {!! Form::close() !!}
@endsection

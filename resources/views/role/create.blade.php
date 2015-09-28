@extends('main')

@section('content')

    <h1>Create Role</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])

    {!! Form::open(['url' => 'roles', 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div style="height: 100%;" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Role Details</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div><div class="form-group">
                        {!! Form::label('display_name', 'Display Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div><div class="form-group">
                        {!! Form::label('description', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Role Permissions</strong></div>
                <div class="form-horizontal">
                    @foreach($permissionsAvailable as $permission)
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-5">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox($permission->display_name, $permission->id, false) !!} {{$permission->display_name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Create Role', ['class' => 'btn btn-info form-control', 'name' => 'createRoleButton']) !!}
        </div>
    </div>

    <input id="permissionsAvailable" name="permissionsAvailable" type="hidden" value="@foreach ($permissionsAvailable as $permission){{ ':'.$permission->id  }}@endforeach">
    <input id="permissionsApplied" name="permissionsApplied" type="hidden" value="">
    {!! Form::close() !!}

@endsection

@extends('master')

@section('content')

    <h1>Edit Role</h1>
    <hr/>

    {!! Form::model($role, ['method' => 'PATCH', 'action' => ['RoleController@update', $role->id], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('display_name', 'Display Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('description', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div>


    @foreach ($role->permissions as $permission)
        <p>{{ $permission->name }}</p>
    @endforeach

    {{--<div class="form-group">--}}
                        {{--{!! Form::label('permissions->permissions_id', 'Permissions: ', ['class' => 'col-sm-3 control-label']) !!}--}}
                        {{--<div class="col-sm-6">--}}
                            {{--{!! Form::text('permissions->permissions_id', null, ['class' => 'form-control']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
    
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

@endsection

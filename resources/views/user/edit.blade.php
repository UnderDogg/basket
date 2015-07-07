@extends('master')

@section('content')

    <h1>Edit User</h1>
    <hr/>

    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UserController@update', $user->id], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('password', 'Password: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::password('password', null, ['class' => 'form-control col-sm-6']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('merchant_id', 'Merchant Id: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('merchant_id', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('locations', 'Locations: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('locations', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div>
    
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

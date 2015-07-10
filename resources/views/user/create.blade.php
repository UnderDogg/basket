@extends('master')

@section('content')

    <hr/>
    @if ($errors->any())
        <div id="actionMessage" hidden="hidden">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </div>
        </div>
    @endif

    <h2>{{ Str::upper(' create a new ' . Request::segment(1)) }}</h2>
    <hr/>

    {!! Form::open(['url' => 'user', 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">ROLE DETAILS</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('password', 'Password: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::password('password', ['class' => 'form-control'], null) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('merchant_id', 'Merchant Id: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('merchant_id', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('locations', 'Locations: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('locations', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div>
                </div>
            </div>
        </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Create', ['class' => 'btn btn-primary form-control']) !!}
        </div>    
    </div>
    {!! Form::close() !!}

@endsection

@extends('main')

@section('content')

    <h1>Account management
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/edit" class="btn btn-default">
                <span class="glyphicon glyphicon-edit"></span> Edit
            </a>
        </div>
    </h1>

    @include('includes.page.breadcrumb')
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">User Details</h3></div>
        <div class="panel-body">
            {!! Form::model($user, ['class' => 'form-horizontal']) !!}
            <div class="row">
                <div class="col-xs-2 col-sm-4 col-md-2 col-lg-2">
                    <div class="thumbnail">
                        <img src="{{ '//www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?size=200' }}" alt="...">
                    </div>
                </div>
                <div class="col-xs-10 col-sm-8 col-md-10 col-lg-10">
                    &nbsp;
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', 'Email: ', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@endsection

@extends('main')

@section('content')

    <h2>Edit account details</h2>
    @include('includes.page.breadcrumb')
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">USER DETAILS</h3></div>
        <div class="panel-body">
            <div class="row">
                {!! Form::model($user, array('method' => 'post')) !!}
                <div class="col-xs-2 col-sm-4 col-md-2 col-lg-2">
                    <div class="thumbnail">
                        <img src="{{ '//www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?size=200' }}" alt="...">
                    </div>
                </div>
                <div class="col-xs-10 col-sm-8 col-md-10 col-lg-10">
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
                        {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Update details', array('class' => 'btn btn-default pull-right')) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="row">
                <div class="col-xs-10 col-sm-8 col-md-10 col-lg-10 pull-right">
                    {!! Form::open(array('url' => Request::url() . '/password', 'method' => 'post')) !!}
                    <div class="form-group">
                        {!! Form::label('old_password', 'Old password') !!}
                        {!! Form::password('old_password', ['placeholder' => 'Old password', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('new_password', 'Password') !!}
                        {!! Form::password('new_password', ['placeholder' => 'New password', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('new_password_confirmation', 'Confirm new password') !!}
                        {!! Form::password('new_password_confirmation', ['placeholder' => 'Confirm new password', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Change password', array('class' => 'btn btn-default pull-right')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection

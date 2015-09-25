@extends('main')

@section('content')

    <h1>Edit Installation</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $installations->name]])

    <p>&nbsp;</p>
    {!! Form::model($installations, ['method' => 'PATCH', 'action' => ['InstallationsController@update', $installations->id], 'class' => 'form-horizontal']) !!}
    <div class="col-xs-12">

        <div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
            @if($installations->active == 1)
                    {!! Form::input('checkbox', 'active', null, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small', 'value' => '1']) !!}
                @else
                    {!! Form::input('checkbox', 'active', null, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('validity', 'Validity Period (in seconds)', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('validity', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('custom_logo_url', 'Custom Logo (URL)', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('custom_logo_url', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('location_instruction', 'Additional Instruction: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textArea('location_instruction', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('disclosure', 'In Store Disclosure: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textArea('disclosure', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('ext_return_url', 'Return URL', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('ext_return_url', $installations->ext_return_url, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('ext_notification_url', 'Notification URL', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('ext_notification_url', $installations->ext_notification_url, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-info', 'name' => 'saveChanges']) !!}
            </div>
        </div>

    </div>
    <p>&nbsp;</p>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>

    </div>

    {!! Form::close() !!}

@endsection

@section('scripts')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
@endsection

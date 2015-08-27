@extends('master')

@section('content')

    <h2>{{ Str::upper(' edit ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $installations->name]])

    <p>&nbsp;</p>

    {!! Form::model($installations, ['method' => 'PATCH', 'action' => ['InstallationsController@update', $installations->id], 'class' => 'form-horizontal']) !!}
    <div class="col-xs-12">

        <div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
            @if($installations->active == 1)
                    {!! Form::input('checkbox', 'active', null, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small', 'value' => '1']) !!}
                @else
                    {!! Form::input('checkbox', 'active', null, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('validity', 'Validity Period (in seconds)', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('validity', $installations->validity, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('custom_logo_url', 'Custom Logo (URL)', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('custom_logo_url', $installations->custom_logo_url, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('location_instruction', 'Additional Instruction: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textArea('location_instruction', $installations->location_instruction, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('disclosure', 'In Store Disclosure: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textArea('disclosure', $installations->disclosure, ['class' => 'form-control']) !!}
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

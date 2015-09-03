@extends('main')

@section('content')

    <h1>Edit Merchant</h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $merchants->name]])

    <p>&nbsp;</p>
    {!! Form::model($merchants, ['method' => 'PATCH', 'action' => ['MerchantsController@update', $merchants->id], 'class' => 'form-horizontal']) !!}
    <div class="col-xs-12">
        <div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('token', 'Token: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('token', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('active', 'Active: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                <label class="checkbox-inline">
                    @if($merchants->active == 1)
                        {!! Form::input('checkbox', 'active', 1, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @else
                        {!! Form::input('checkbox', 'active', 0, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @endif
                </label>
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

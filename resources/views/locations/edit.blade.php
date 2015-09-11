@extends('main')

@section('content')

    <h1>Edit Location</h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $location->name]])


    <p>&nbsp;</p>
    {!! Form::model($location, [
        'method' => 'PATCH',
        'action' => ['LocationsController@update', $location->id],
        'class' => 'form-horizontal'
    ]) !!}

        <div class="form-group">
            {!! Form::label('reference', 'Reference:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::text('reference', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    @if($location->active == 1)
                        {!! Form::input('checkbox', 'active', 1, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @else
                        {!! Form::input('checkbox', 'active', 0, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @endif
                </label>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('name', 'Name:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('email', 'Email:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('address', 'Address:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('address', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-info', 'name' => 'saveChanges']) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection

@section('scripts')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
@endsection

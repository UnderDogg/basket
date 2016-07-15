@extends('main')

@section('content')

    <h1>Add Merchant Payment</h1>
    @include('includes.page.breadcrumb', ['over' => [1 => $application->installation->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

    {!! Form::open( ['method'=>'post', 'class' => 'form-horizontal'] ) !!}
    <div class="alert alert-warning">
        <p>Please confirm that you would like to add a merchant payment to this application. Please note that you will not be able to reverse this later.</p>
    </div>
    <div class="container-fluid">

        <div class="form-group">
            {!! Form::label('amount', 'Payment Amount') !!}
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {!! Form::text('amount', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('effective_date', 'Effective Date') !!}
            <div class="input-group">
                <input name="effective_date" type="text" class="form-control" id="datepicker_to">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </div>
            </div>
        </div>
        <p>&nbsp;</p>
        <div class="form-group pull-right">
            {!! Form::submit('Add Payment', [
            'class' => 'btn btn-success',
            'name' => 'confirmRequest'
            ]) !!}
            <a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

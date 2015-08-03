@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' create a new ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb')

    {!! Form::open(['url' => 'users', 'class' => 'form-horizontal']) !!}
    <p>&nbsp;</p>

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">USER DETAILS</h3></div>
                <div class="panel-body">

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
                            {!! Form::password('password', ['class' => 'form-control'], null) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('merchant', 'Merchant: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('merchant_id', $merchants, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div style="height: 100%;" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">USER LOCATIONS</h3>
                </div>
                <div class="panel-body panel-tight-space">
                    <div class="col-xs-6">
                        <h3 class="panel-title">Applied Locations</h3>
                        <hr class="hr-tight">
                    </div>
                    <div class="col-xs-6">
                        <h3 class="panel-title">Locations Available</h3>
                        <hr class="hr-tight">
                    </div>
                </div>
                <div class="panel-body panel-tight-space" style="display: table; margin-bottom: 20px;">
                    <div style="display: table-cell; float: none;" id="permissionsAppliedHolder" class="connectedSortable col-xs-6">
                        @foreach ($locationsApplied as $location)
                            <div name="{{ $location->id }}" class="draggableItem">{{ $location->name }}</div>
                        @endforeach
                    </div>
                    <div style="display: table-cell; float: none;" id="permissionsAvailableHolder" class="connectedSortable col-xs-6">
                        @foreach ($locationsAvailable as $location)
                            <div name="{{ $location->id }}" class="draggableItem">{{ $location->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <p>&nbsp;</p>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Create User', ['class' => 'btn btn-info form-control', 'name' => 'createUserButton']) !!}
        </div>
    </div>

    <input id="permissionsApplied" name="locationsApplied" type="hidden" value="@foreach ($locationsApplied as $location){{ ':'.$location->id  }}@endforeach">
    <input id="permissionsAvailable" name="locationsAvailable" type="hidden" value="">
    {!! Form::close() !!}

@endsection

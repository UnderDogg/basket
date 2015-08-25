@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>Update User Locations</h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $user->name]])

    <p>&nbsp;</p>
    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@updateLocations', $user->id], 'class' => 'form-horizontal']) !!}

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

    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>
    </div>

    <input id="permissionsApplied" name="locationsApplied" type="hidden" value="@foreach ($locationsApplied as $location){{ ':'.$location->id  }}@endforeach">
    <input id="permissionsAvailable" name="locationsAvailable" type="hidden" value="">
    {!! Form::close() !!}

@endsection

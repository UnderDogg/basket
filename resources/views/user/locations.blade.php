@extends('main')

@section('content')

    <h1>Update User Locations</h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $user->name]])

    <p>&nbsp;</p>
    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@updateLocations', $user->id], 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>User Locations</strong></div>
                <div class="panel-body panel-tight-space">
                    <div class="col-xs-6">
                        <div class="panel locationsPanel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Applied Locations</h3>
                                <hr class="hr-tight">
                            </div>
                            <div class="panel-body panel-tight-space">
                                <div id="permissionsAppliedHolder" class="connectedSortable col-xs-6">
                                    @if($locationsApplied !== null)
                                        @foreach ($locationsApplied as $location)
                                            <div name="{{ $location->id }}" class="draggableItem">{{ $location->name }}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="panel locationsPanel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Locations Available</h3>
                                <hr class="hr-tight">
                            </div>
                            <div class="panel-body panel-tight-space">
                                <div id="permissionsAvailableHolder" class="connectedSortable col-xs-6">
                                    @if($locationsAvailable !== null)
                                        @foreach ($locationsAvailable as $location)
                                            <div name="{{ $location->id }}" class="draggableItem">{{ $location->name }}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

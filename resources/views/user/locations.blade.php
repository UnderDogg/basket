@extends('main')

@section('content')

    <h1>Update User Locations</h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $user->name]])

    <p>&nbsp;</p>
    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@updateLocations', $user->id], 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Locations</strong></div>
                <div class="form-horizontal">
                    @if($locationsApplied !== null)
                        @foreach ($locationsApplied as $location)
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-5">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox($location->name, $location->id, true) !!} {{$location->name}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if($locationsAvailable !== null)
                        @foreach ($locationsAvailable as $location)
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-5">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox($location->name, $location->id, false) !!} {{$location->name}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection

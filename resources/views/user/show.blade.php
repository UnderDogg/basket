@extends('main')

@section('content')

    <h1>
        View User
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/edit" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Edit</a>
            <a href="{{Request::url()}}/locations" class="btn btn-default"><span class="glyphicon glyphicon-map-marker"></span> Locations</a>
            <a href="{{Request::url()}}/delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle"></span> Delete</a>
        </div>
    </h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $user->name]])

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#part1">Application Details</a></li>
    </ul>
    <br/>
    <div class="tab-content">
        <div id="part1" class="tab-pane fade in active">
            <br/>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Key Information</strong></div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Name</dt>
                        <dd>{!! $user->name !!}</dd>
                        <dt>Email</dt>
                        <dd>{!! $user->email !!}</dd>
                        <dt>Merchant</dt>
                        @if($user->merchant !== null)
                            <a href="/merchants/{{$user->merchant->id}}">
                                <dd>{!! $user->merchant->name !!}</dd>
                            </a>
                        @else
                            <dd></dd>
                        @endif
                        @if($user->locations != null )
                            <dt>Locations</dt>
                            @foreach ($user->locations as $location)
                                <dd>{!! $location->name !!}</dd>
                            @endforeach
                            <dd></dd>
                        @endif
                        <dt>Roles</dt>
                        @if($user->roles !== null)
                            @foreach ($user->roles as $role)
                                <dd>{!! $role->display_name !!}</dd>
                            @endforeach
                            <dd></dd>
                        @else
                            <dd></dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

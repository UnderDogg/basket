@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>
        {{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        @include('includes.page.show_details_button_group', ['id'=>$user->id,'edit'=>true,'delete'=>true, 'locations'=>true])
    </h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $user->name]])

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
                            <a href="{{Request::segment(0)}}/merchants/{{$user->merchant->id}}">
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

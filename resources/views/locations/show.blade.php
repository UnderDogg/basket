@extends('main')

@section('content')

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/edit" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Edit</a>
            @if($location->installation !== null)
                <a href="{{Request::segment(0)}}/installations/{{$location->installation->id}}" class="btn btn-default"><span class="glyphicon glyphicon-hdd"></span> Installation</a>
            @endif

            <a href="{{Request::url()}}/delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle"></span> Delete</a>
        </div>
    </h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $location->name]])
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#part1">Location Details</a></li>
    </ul>
    <div class="tab-content">
        <div id="part1" class="tab-pane fade in active">
            <br/>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Key Information</strong></div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Reference</dt>
                        <dd>{!! $location->reference !!}</dd>
                        <dt>Name</dt>
                        <dd>{!! $location->name !!}</dd>
                        <dt>Active Status</dt>
                        <dd>
                            @if( $location->active == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                            @elseif( $location->active == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Active</span>
                            @endif
                        </dd>
                        <dt>Installation</dt>
                        @if($location->installation !== null)
                            <a href="{{Request::segment(0)}}/installations/{{$location->installation->id}}">
                                <dd>{!! $location->installation->name  !!}</dd>
                            </a>
                        @else
                            <dd></dd>
                        @endif
                        <dt>Location Email Address</dt>
                        <dd>{!! $location->email !!}</dd>
                        <dt>Location Address</dt>
                        <dd>{!! $location->address !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

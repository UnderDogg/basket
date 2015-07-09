@extends('master')

@section('content')

    <hr/>
    <div class="col-xs-12">
        <h2>
            <a style="margin-bottom: 7px;" href="{{ '/' . Request::segment(1) }}" class="btn btn-info btn-xs" role="button">Back</a>
            {{ Str::upper(' ' . $role->display_name) }}
        </h2>
        <hr/>
    </div>

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#fragment-1">Role Details</a></li>
            <li role="presentation"><a href="#fragment-2">Active Permissions</a></li>
            <li role="presentation"><a href="#fragment-3">Maybe Users With This Role?</a></li>
        </ul>
        <div class="col-xs-12">&nbsp;</div>
        <div id="fragment-1">

            <div class="col-xs-12 col-md-6">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Role ID: </strong> {{ $role->id  }}
                            </li>
                            <li class="list-group-item">
                                <strong>Role Code: </strong> {{ $role->name  }}
                            </li>
                            <li class="list-group-item">
                                <strong>Display Name: </strong> {{ $role->display_name  }}
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-md-6">

                <div class="input-group input-group">
                    <span class="input-group-addon" id="sizing-addon1">Description</span>
                    <div class="form-control" aria-describedby="sizing-addon1">{{ $role->description  }}</div>
                </div>

            </div>

        </div>
        <div id="fragment-2">

        </div>
        <div id="fragment-3">

        </div>
    </div>

    {{--<h1>Role</h1>--}}
    {{--<div class="table-responsive">--}}
        {{--<table class="table table-bordered table-striped table-hover">--}}
            {{--<tr>--}}
                {{--<th>ID.</th><th>Name</th>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>{{ $role->id }}</td><td>{{ $role->name }}</td>--}}
            {{--</tr>--}}
        {{--</table>--}}
    {{--</div>--}}

@endsection

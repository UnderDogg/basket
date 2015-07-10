@extends('master')

@section('content')

    <hr>
    @if( $role->message !== null )
        <div id="actionMessage" hidden="hidden">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Success</strong> {{ $role->message }}
            </div>
        </div>
    @endif

    <h1>Roles <a href="{{ url('/role/create') }}" name="addNewButton" class="btn btn-info pull-right btn-sm">Add New Role</a></h1>
    <hr>

    <div class="panel panel-default">
        <div class="panel-heading"><h4>Application Roles</h4></div>

        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID</th>
                <th>Display Name</th>
                <th class="hidden-xs hidden-sm">Role Code</th>
                <th class="hidden-xs hidden-sm">Description</th>
                <th>Actions</th>
            </tr>
            {{-- */$x=0;/* --}}
            @foreach($role as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td class="col-xs-1">{{ $item->id }}</td>
                    <td>{{ $item->display_name }}</td>
                    <td class="hidden-xs hidden-sm">{{ $item->name }}</td>
                    <td class="hidden-xs hidden-sm">{{ str_limit($item->description, 60) }}</td>

                    {{--Action Buttons--}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        <div class="btn-group">
                            <a href="{{ url('/role', $item->id) }}" type="button" class="btn btn-default btn-xs"> VIEW </a>
                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ url('/role/'.$item->id.'/edit') }}">Edit</a></li>
                                {{--<li><a href="#"> NEW BUTTON SPACER </a></li>--}}
                                {{--<li><a href="#"> NEW BUTTON SPACER </a></li>--}}
                                <li role="separator" class="divider"></li>
                                {!! Form::open(['method'=>'delete','action'=>['RoleController@destroy',$item->id]]) !!}
                                <button type="submit" class="btn btn-xs dropdown-delete">
                                    <li><a>Delete</a></li>
                                </button>
                                {!! Form::close() !!}
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>


@endsection

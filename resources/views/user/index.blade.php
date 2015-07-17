@extends('master')

@section('content')

    <hr>
    @if( $user->message !== null )
        <div id="actionMessage" hidden="hidden">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Success</strong> {{ $user->message }}
            </div>
        </div>
    @endif

    <h1>Users <a href="{{ url('/user/create') }}" class="btn btn-info pull-right btn-sm">Add New User</a></h1>
    <hr>

    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th class="text-right">Actions</th>
            </tr>
            {{-- */$x=0;/* --}}
            @foreach($user as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td class="col-xs-1">{{ $item->id }}</td>
                    <td><a href="{{ url('/user', $item->id) }}">{{ $item->name }}</a></td>
                    <td>{{ $item->email }}</td>
                    {{--Action Buttons--}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        <div class="btn-group">
                            <a href="{{ url('/user', $item->id) }}" style="color: #777 !important;" type="button" class="btn btn-default btn-xs"> VIEW </a>
                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ url('/user/'.$item->id.'/edit') }}">Edit</a></li>
                                {{--<li><a href="#"> NEW BUTTON SPACER </a></li>--}}
                                {{--<li><a href="#"> NEW BUTTON SPACER </a></li>--}}
                                <li role="separator" class="divider"></li>
                                {!! Form::open(['method'=>'delete','action'=>['UserController@destroy',$item->id]]) !!}
                                <button style="" type="submit" class="btn btn-xs dropdown-delete">
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

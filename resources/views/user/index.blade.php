@extends('master')

@section('content')

    <h1>Users <a href="{{ url('/user/create') }}" class="btn btn-primary pull-right btn-sm">Add New User</a></h1>
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
                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">
                        <div class="btn-group" role="group" aria-label="...">
                            <a href="{{ url('/user/'.$item->id.'/edit') }}">
                                <button type="submit" class="btn btn-primary btn-xs">Update</button>
                            </a>
                            {!! Form::open(['method'=>'delete','action'=>['UserController@destroy',$item->id], 'style' => 'display:inline']) !!}
                                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection

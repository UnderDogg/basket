@extends('master')

@section('content')

    <h1>Roles <a href="{{ url('/role/create') }}" class="btn btn-primary pull-right btn-sm">Add New Role</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID</th><th>Display Name</th><th class="hidden-xs hidden-sm">Name</th><th class="hidden-xs hidden-sm">Description</th><th>Actions</th>
            </tr>
            {{-- */$x=0;/* --}}
            @foreach($role as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td class="col-xs-1">{{ $item->id }}</td>
                    <td><a href="{{ url('/role', $item->id) }}">{{ $item->display_name }}</a></td>
                    <td class="hidden-xs hidden-sm">{{ $item->name }}</td>
                    <td class="hidden-xs hidden-sm">{{ str_limit($item->description, 60) }}</td>
                    <td class="col-xs-4 col-sm-3 col-md-2 text-right">
                        <div class="btn-group" role="group" aria-label="...">
                            <a href="{{ url('/role/'.$item->id.'/edit') }}">
                                <button type="submit" class="btn btn-primary btn-xs">Update</button>
                            </a>
                            {!! Form::open(['method'=>'delete','action'=>['RoleController@destroy',$item->id], 'style' => 'display:inline']) !!}
                            <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection

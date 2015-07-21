@extends('master')

@section('content')

    <h1>Applications <a href="{{ url('/applications/create') }}" class="btn btn-primary pull-right btn-sm">Add New Applications</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>SL.</th><th>Name</th><th>Actions</th>
            </tr>
            {{-- */$x=0;/* --}}
            @foreach($applications as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td><a href="{{ url('/applications', $item->id) }}">{{ $item->name }}</a></td>
                    <td><a href="{{ url('/applications/'.$item->id.'/edit') }}"><button type="submit" class="btn btn-primary btn-xs">Update</button></a> / {!! Form::open(['method'=>'delete','action'=>['ApplicationsController@destroy',$item->id], 'style' => 'display:inline']) !!}<button type="submit" class="btn btn-danger btn-xs">Delete</button>{!! Form::close() !!}</td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection

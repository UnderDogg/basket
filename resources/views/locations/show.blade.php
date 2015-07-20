@extends('master')

@section('content')

    <h1>Location</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID.</th><th>Name</th>
            </tr>
            <tr>
                <td>{{ $location->id }}</td><td>{{ $location->name }}</td>
            </tr>
        </table>
    </div>

@endsection

@extends('master')

@section('content')

    <h1>Role</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID.</th><th>Name</th>
            </tr>
            <tr>
                <td>{{ $role->id }}</td><td>{{ $role->name }}</td>
            </tr>
        </table>
    </div>

@endsection

@extends('master')

@section('content')

    <h1>Application</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID.</th><th>Name</th>
            </tr>
            <tr>
                <td>{{ $application->id }}</td><td>{{ $application->name }}</td>
            </tr>
        </table>
    </div>

@endsection

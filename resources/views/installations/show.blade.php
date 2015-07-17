@extends('layouts.master')

@section('content')

    <h1>Installation</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID.</th><th>Name</th>
            </tr>
            <tr>
                <td>{{ $installation->id }}</td><td>{{ $installation->name }}</td>
            </tr>
        </table>
    </div>

@endsection

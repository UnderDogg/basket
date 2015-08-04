@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>MERCHANTS
    </h1>
    @include('includes.page.breadcrumb')

        <div class="panel-heading"><h4>IP Address Management</h4></div>
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>ID</th>
                <th>IP</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
            @foreach($ips as $ip)

                <tr>
                    <td>{{$ip->getId()}}</td>
                    <td>{{$ip->getIp()}}</td>
                    <td>{{$ip->getActive()}}</td>
                    <td class="text-right">
                        <a href="{{Request::URL()}}/{{$ip->getId()}}/delete" type="button" class="btn btn-default btn-xs"> View </a>
                    </td>
                </tr>
            @endforeach
        </table>

    {!! Form::open(array('action' => 'IpsController@delete', 'method' => 'post', 'class' => 'form-inline')) !!}
        <div class="control-group">
            <div>
                <input name="ip" type="text" placeholder="Add new IP address">
                <button type="submit" class="btn"><i class="icon-plus"></i></button>
            </div>
            <span class="help-inline hidden">Please enter a valid IP address</span>
        </div>
    {!! Form::close() !!}

@endsection
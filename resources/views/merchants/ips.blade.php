@extends('main')

@section('content')

    <h2>MERCHANTS</h2>
@include('includes.page.breadcrumb')
    <div class="panel-heading"><h4>Create a new IP address</h4></div>
    {!! Form::open(array('url' => Request::URL() . '/', 'method' => 'post', 'class' => 'addIp')) !!}
        <div class="input-group">
            {!! Form::text('ip', '', array('placeholder' => 'Add new IP address', 'class' => 'form-control')) !!}
            <span class="input-group-btn">
                {!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            </span>
        </div>
    {!! Form::close() !!}
    <div class="has-error">
        <div class="span hidden">
            <label>
                <span class="help-inline text-danger">Please enter a valid IP address</span>
            </label>
        </div>
    </div>

    <div class="panel-heading"><h4>IP Address Management</h4></div>
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>IP</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        @if($ips != null)
            @foreach($ips as $ip)
                <tr>
                    <td>{{$ip->getId()}}</td>
                    <td>{{$ip->getIp()}}</td>
                    <td>
                        @if($ip->getActive() == 0 )
                            <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                        @elseif($ip->getActive() == 1  )
                            <span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Active</span>
                        @endif
                    </td>
                    <td class="text-right">
                    {!! Form::open(array('url' => Request::URL() .'/'. $ip->getId(), 'method' => 'delete', 'class' => 'form-inline')) !!}
                        {!! Form::button('<i class="icon-remove icon-white"></i> Delete', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) !!}
                    {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan=11>No IP addresses have been found.</td>
            </tr>
        @endif
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("form.addIp").submit(function (e) {
                var ip = $(this).find("input[name=ip]");
                if (ip.val().match(/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)) return true;

                ip.parents(".input-group").addClass("error");
                $("div.span").removeClass("hidden");
                e.preventDefault();

            });
        });
    </script>
@endsection

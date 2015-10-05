@extends('main')

@section('content')

    <h1>IP Addresses</h1>
    @include('includes.page.breadcrumb', ['over' => [2 => 'IP Addresses']])
    <h4>Create a new IP address</h4>
    &nbsp;
    {!! Form::open(array('url' => Request::URL() . '/', 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="form-group">
            {!! Form::label('ip', 'IP Address: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('ip', '', ['placeholder' => 'IP Address', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                {!! Form::submit('Add IP Address', ['class' => 'btn btn-info', 'name' => 'createIpAddressButton']) !!}
            </div>
        </div>
    {!! Form::close() !!}

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
        validation = {
            fields: {
                ip: {
                    validators: {
                        notEmpty: {
                            message: 'The ip address cannot be empty'
                        },
                        ip: {
                            ipv6: false,
                            message: 'Please enter a valid IP address'
                        },
                        stringLength: {
                            max: 45,
                            message: 'The ip address must not be greater than 45 characters'
                        }
                    }
                }
            }
        };
    </script>
@endsection

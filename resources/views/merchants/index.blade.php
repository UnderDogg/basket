@extends('main')

@section('content')

    <h1>Merchants
        <a href="{{ url('/merchants/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New Merchant</a>
    </h1>
    @include('includes.page.breadcrumb')
    <p>
        <strong>{{ $merchants->count() }}</strong> Record(s) / <strong>{{ $merchants->total() }}</strong> Total
    </p>

    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>Name</th>
            <th class="hidden-xs hidden-sm">Company Name</th>
            <th class="hidden-xs hidden-sm">Min. Amount Settled</th>
            <th class="col-sm-2 col-md-1">Linked</th>
            <th>Actions</th>
        </tr>

        @forelse($merchants as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="hidden-xs hidden-sm">{{ $item->ext_company_name }}</td>
                <td class="hidden-xs hidden-sm">{{ $item->ext_minimum_amount_settled }}</td>
                <td class="col-sm-2 col-md-1">
                    @if( $item->linked == 0 )
                        <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Unlinked</span>
                    @elseif( $item->linked == 1 )
                        <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i> Linked</span>
                    @endif
                </td>

                {{-- ACTION BUTTONS --}}
                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-right">
                    <div class="btn-group">
                        <a href="{{Request::URL()}}/{{$item->id}}" type="button" class="btn btn-default btn-xs"> View </a>
                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{Request::URL()}}/{{$item->id}}/edit">Edit</a></li>
                                <li><a href="{{Request::URL()}}/{{$item->id}}/synchronise">Synchronise</a></li>
                                <li><a href="/installations/?merchant_id={{$item->id}}">Installations</a></li>
                                <li><a href="{{Request::URL()}}/{{$item->id}}/ips">View IP's</a></li>
                                <li class="divider"></li>
                                <li><a href="{{Request::URL()}}/{{$item->id}}/settlements">Settlements</a></li>
                                <li><a href="{{Request::URL()}}/{{$item->id}}/partial-refunds">Partial Refunds</a></li>
                            </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><em>No records found</em></td></tr>
        @endforelse
    </table>

    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $merchants->appends(Request::except('page'))->render() !!}
@endsection

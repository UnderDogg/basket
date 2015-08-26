@extends('master')

@section('content')

    <h1>Merchants
        <a href="{{ url('/merchants/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New Merchant</a>
    </h1>
    @include('includes.page.breadcrumb')
    @include('includes.form.record_counter', ['object' => $merchants])

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
                <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                    @include('includes.form.record_actions', [
                    'id' => $item->id,
                        'actions' => [
                            'edit' => 'Edit',
                            'synchronise' => 'Synchronise',
                            'installations/synchronise' => 'Synchronise Installations',
                            'ips' => "View IP's",
                        ]
                    ])
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><em>No records found</em></td></tr>
        @endforelse
    </table>

    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $merchants->appends(Request::except('page'))->render() !!}
@endsection

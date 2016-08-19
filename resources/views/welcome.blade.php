@extends('main')

@section('content')

    <div>

        <h1>DASHBOARD</h1>
        <hr>

        @if(count($user->locations) > 0)
            <h2>Apply for Finance</h2>

            <table class="table table-striped table-hover table-va-middle">

                <tr>
                    <th>Reference</th>
                    <th>Name</th>
                    <th class="hidden-xs hidden-sm">Address</th>
                    <th class="hidden-xs hidden-sm">Email Address</th>
                    <th>&nbsp;</th>
                </tr>

                @if($locationsDisplayed = 0) @endif
                @foreach($user->locations as $location)
                    @if($location->installation->finance_offers > 0)
                        @if($locationsDisplayed++) @endif
                        <tr>
                            <td>{{ $location->reference }}</td>
                            <td>{{ $location->name }}</td>
                            <td class="hidden-xs hidden-sm">{{ $location->address }}</td>
                            <td class="hidden-xs hidden-sm">{{ $location->email }}</td>
                            <td class="text-right"><a href="/locations/{{ $location->id }}/applications/make" class="btn btn-success">Make Application</a></td>
                        </tr>
                    @endif
                @endforeach
                @if($locationsDisplayed == 0) <tr><td colspan="5">No locations available to make an application.</td></tr>@endif
            </table>
        @endif

        <h2>Support Details</h2>

        <div class="col-md-4">
            <div>
                <h4>Customer Enquiries</h4>
                <ul>
                    <li><strong>Tel:</strong> <a href="tel:03333444224">03333 444 224</a></li>
                    <li><strong>Email:</strong> <a href="mailto:hello@afforditnow.com">hello@afforditnow.com</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div>
                <h4>Retailer Enquiries</h4>
                <ul>
                    <li><strong>Tel:</strong><a href="tel:03333444226"> 03333 444 226</a></li>
                    <li><strong>Email:</strong> <a href="mailto:retailer@afforditnow.com">retailer@afforditnow.com</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div>
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="https://s3-eu-west-1.amazonaws.com/paybreak-assets/afforditnow-retailer-user-guide.pdf" target="_blank">Retailer Back Office Guide</a></li>
                    <li><a href="http://www.afforditnow.com/retailer/retailer-developer-support/" target="_blank">Retailer Developer Support</a></li>
                    <li><a href="http://status.afforditnow.com/" target="_blank">Service Status</a></li>
                </ul>
            </div>
        </div>
    </div>

@endsection

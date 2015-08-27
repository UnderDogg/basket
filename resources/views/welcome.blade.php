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

                @foreach($user->locations as $location)
                    <tr>
                        <td>{{ $location->reference }}</td>
                        <td>{{ $location->name }}</td>
                        <td class="hidden-xs hidden-sm">{{ $location->address }}</td>
                        <td class="hidden-xs hidden-sm">{{ $location->email }}</td>
                        <td class="text-right"><a href="/locations/{{ $location->id }}/applications/make" class="btn btn-success">Make Application</a></td>
                    </tr>

                @endforeach
            </table>
        @endif

        <h2>Support Details</h2>

        <div class="col-md-6">
            <div>
                <h4>Customer Enquiries</h4>
                <ul>
                    <li><strong>Tel:</strong> 03333 444 224</li>
                    <li><strong>Email:</strong> hello@paybreak.com</li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div>
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="http://www.afforditnow.com/retailer/retailer-developer-support/" target="_blank">Retailer Developer Support</a>
                        <ul>
                            <li><a href="http://paybreak.github.io/retailer-integration-guide/" target="_blank">Integration Guide</a></li>
                            <li><a href="http://www.afforditnow.com/wp-content/uploads/2015/02/afforditnow_images_v1.zip">Download</a> Promotional Assets (.zip file)</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection

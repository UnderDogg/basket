<h1>Finance Application {{ $application->ext_id }} has been Approved</h1>

<h2>Application Details</h2>
<ul>
    <li>Order Reference: <strong>{{ $application->ext_order_reference }}</strong></li>
    <li>Application/Payment Reference: <strong>{{ $application->ext_id }}</strong></li>
</ul>

<h2>Customer Details</h2>
<ul>
    <li>Name: <strong>{{ $application->ext_customer_title }} {{ $application->ext_customer_first_name }} {{ $application->ext_customer_last_name }}</strong></li>
    <li>Email: <strong>{{ $application->ext_customer_email_address }}</strong></li>
    <li>
        Address:
        <address>
            @if($application->ext_application_address_abode)
            {{ $application->ext_application_address_abode }}<br>
            @endif
            @if($application->ext_application_address_building_name)
            {{ $application->ext_application_address_building_name }}<br>
            @endif
            {{ $application->ext_application_address_building_number }} {{ $application->ext_application_address_street }}<br>
            @if($application->ext_application_address_locality)
            {{ $application->ext_application_address_locality }}<br>
            @endif
            @if($application->ext_application_address_town)
            {{ $application->ext_application_address_town }}<br>
            @endif
            {{ $application->ext_application_address_postcode }}<br>
        </address>
    </li>
    <li>Home Phone number: <strong>{{ $application->ext_customer_phone_home }}</strong></li>
    <li>Mobile Phone number: <strong>{{ $application->ext_customer_phone_mobile }}</strong></li>
</ul>

<h2>Credit Details</h2>
<ul>
    <li>Order Amount: {{ money_format('%.2n', $application->ext_finance_order_amount/100) }}</li>
    <li>Loan Amount: {{ money_format('%.2n', $application->ext_finance_loan_amount/100) }}</li>
    <li>Deposit Amount: {{ money_format('%.2n', $application->ext_finance_deposit/100) }}</li>
    <li>Subsidy Amount: {{ money_format('%.2n', $application->ext_finance_subsidy/100) }}</li>
    <li>Net Settlement Amount: {{ money_format('%.2n', $application->ext_finance_net_settlementy/100) }}</li>
</ul>

@if($location->installation->location_instruction)
<h2>Additional Instructions</h2>
{!! $location->installation->getLocationInstructionAsHtml() !!}
<hr>
@endif

If you experience any difficulties or need more information please email <a href="mailto:hello@paybreak.com">hello@paybreak.com</a> or phone us on <strong>033 33 444 226</strong>.

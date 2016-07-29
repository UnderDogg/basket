<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>afforditNOW</title>
    <style>
        .button {
            font-size: 16px;
            color: #ffffff;
            text-decoration: none;
            border-radius: 3px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            background-color: {{ (isset($custom_colour_button) ? $custom_colour_button : '#39b54a' ) }};
            border-top: 12px solid {{ (isset($custom_colour_button) ? $custom_colour_button : '#39b54a' ) }};
            border-bottom: 12px solid {{ (isset($custom_colour_button) ? $custom_colour_button : '#39b54a' ) }};
            border-right: 50px solid {{ (isset($custom_colour_button) ? $custom_colour_button : '#39b54a' ) }};
            border-left: 50px solid {{ (isset($custom_colour_button) ? $custom_colour_button : '#39b54a' ) }};
            display: inline-block;
        }
    </style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width: 100% !important; -webkit-text-size-adjust: none; margin: 0; padding: 0; background-color: #FFFFFF !important;">
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="height: 100% !important; margin: 0; padding: 0; width: 100% !important; background-color: #FFFFFF !important;">
        <tr>
            <td align="center" valign="top" style="border-collapse: collapse;">
                <table border="0" cellpadding="0" cellspacing="0" width="700" id="templateContainer" style="border: 10px solid #F1F1F1 !important; overflow: hidden; border-radius: 10px; background-color: #FFFFFF; margin-top: 10px;">
                    <tr>
                        <td align="center" valign="top" style="border-collapse: collapse;">
                            <table border="0" cellpadding="0" cellspacing="0" width="700" id="templateBody">
                                <tr>
                                    <td valign="top" style="border-collapse: collapse;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="top" class="bodyContent" style="border-collapse: collapse; background-color: #FFFFFF; margin-top: 10px;">
                                                    <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top" style="border-collapse: collapse;">
                                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                                    <tr>
                                                                        @if($installation_logo != '')
                                                                            <td valign="top" style="border-collapse: collapse;">
                                                                                <img src="{{ $installation_logo }}" style="max-width: 300px; vertical-align: bottom; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; display: inline;">
                                                                            </td>
                                                                        @endif
                                                                        <td valign="top" style="border-collapse: collapse;">
                                                                            <img src="https://s3-eu-west-1.amazonaws.com/paybreak-assets/ain-logo-standard-medium.png" style="max-width: 300px; vertical-align: bottom; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; display: inline;">
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                                <div style="color: #505050; font-family: Arial; font-size: 14px; line-height: 150%; text-align: left; padding: 20px;">                                <p>Dear {{$customer_title}} {{$customer_last_name}}</p>
                                                                    <p>You are just a couple of clicks away from completing your purchase with <strong>{{ $installation_name }}</strong>.</p>
                                                                    <h2 style="color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#29ABE2' ) }}; display: block; font-family: Arial; font-size: 22px; font-weight: bold; line-height: 100%; margin-top: 0; margin-right: 0; margin-bottom: 20px; margin-left: 0; text-align: left;">How it works</h2>
                                                                    <p>In order to apply for finance and spread the cost. Just check <strong>Your Finance Offer</strong> below and click Apply Now.</p>
                                                                    <p>You will get an instant decision from our simple application process and everything is managed online. It really is that quick and easy.</p>
                                                                    <h2 style="color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#29ABE2' ) }}; display: block; font-family: Arial; font-size: 22px; font-weight: bold; line-height: 100%; margin-top: 0; margin-right: 0; margin-bottom: 20px; margin-left: 0; text-align: left;">Your Finance Offer</h2>
                                                                    <table style="border-collapse: collapse; width: 100%;">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td style="border-collapse: collapse; text-align: left; padding: 4px; color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#29ABE2' ) }};"><strong>Product:</strong></td>
                                                                            <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{$order_description}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="border-collapse: collapse; text-align: left; padding: 4px; color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#29ABE2' ) }};"><strong>Monthly Payment:</strong></td>
                                                                            <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{'&pound;' . number_format($payment_regular/100,2)}} for {{$payments}} months</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="border-collapse: collapse; text-align: left; padding: 4px; color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#29ABE2' ) }}; vertical-align: text-top;"><strong>Finance Details:</strong></td>
                                                                            <td style="border-collapse: collapse; text-align: left; padding: 4px;">
                                                                                <table style="border-collapse: collapse; width: 100%; bgcolor:#00FF00";>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">Purchase Price:</td>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{'&pound;' . number_format($order_amount/100,2)}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">Deposit:</td>
                                                                                        <td style="border-collapse: collapse; btext-align: left; padding: 4px;">{{'&pound;' . number_format($deposit_amount/100,2)}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">Loan Amount:</td>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{'&pound;' . number_format($loan_amount/100,2)}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{$payments}} Monthly payments of:</td>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;"> {{'&pound;' . number_format($payment_regular/100,2)}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">Total Amount Repayable:</td>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{'&pound;' . number_format($total_repayment/100,2)}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">Rate of Interest (fixed):</td>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{number_format($offered_rate, 2)}}%</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">APR Representative</td>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{number_format($apr, 2)}}% </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">Total Charge for Credit:</td>
                                                                                        <td style="border-collapse: collapse; text-align: left; padding: 4px;">{{'&pound;' . number_format($loan_cost/100,2)}}</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        <tr>
                                                                            <td colspan="2" align="right" style="padding: 5px;">
                                                                                <table border="0" cellspacing="0" cellpadding="0">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a href="{{$apply_url}}" target="_blank" class="button">Apply Now &rarr;</a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <br>
                                                                    <hr style="border: none; color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#29ABE2' ) }}; background-color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#29ABE2' ) }}; height: 2px;">
                                                                    <p align="right" style="color: #707070;">
                                                                        <strong>{{ isset($installation_name) ? $installation_name : '' }}</strong><br>
                                                                        {{ isset($retailer_telephone) ? $retailer_telephone : '' }}<br>
                                                                        <a style="text-decoration: none; color: {{ (isset($custom_colour_highlight) ? $custom_colour_highlight : '#39b54a' ) }};" href="{{ isset($retailer_url) ? $retailer_url : '' }}">{{ isset($retailer_url) ? $retailer_url : '' }}</a>
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-collapse: collapse;">
                            <table border="0" cellpadding="10" cellspacing="0" width="700" id="templateFooter" style="background: #F1F1F1;">
                                <tr>
                                    <td valign="top" class="footerContent" style="border-collapse: collapse;">
                                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                            <tr>
                                                <td colspan="2" valign="middle" id="utility" style="border-collapse: collapse;">
                                                    <div style="color: #707070; font-family: Arial; font-size: 12px; line-height: 125%;">afforditNOW is a suite of products offered by PayBreak Limited, a company authorised and regulated by the Financial Conduct Authority. PayBreak Limited is a UK registered company, registration number 7440512 with registered offices at Grosvenor House, 20 Barrington Road, Altrincham, WA14 1HB.
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
            </td>
        </tr>
    </table>
</center>
</body>
</html>

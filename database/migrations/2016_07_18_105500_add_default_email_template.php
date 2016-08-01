<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author EB
 * Class AddDefaultEmailTemplate
 */
class AddDefaultEmailTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Database\Eloquent\Model::unguard();
        $template = new \App\Basket\Template();
        $template->html = '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link href=\'https://fonts.googleapis.com/css?family=Ubuntu\' rel=\'stylesheet\' type=\'text/css\'>
    <style>
        * {
            font-family: Helvetica, Arial;
        }
        .top-space {
            margin-top: 30px;
        }
        .top-break {
            border-top: 10px solid #29abe2;
        }
        .display-right {
            float: right;
        }
        .wrapper {
            margin: auto 50px;
        }
        .introduction-text {
            font-size: 16px;
        }
        .retailer-logo {
            max-height: 100px;
            max-width: 350px;
        }
        .size {
            float: left;
        }
        .size-4 {
            width: 33%;
        }
        .size-8 {
            width: 67%;
        }
        .text-right {
            text-align: right;
            padding-right: 20px;
        }
        /* Bootstrap Button */
        .apply-btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border-radius: 4px;
            color: #fff;
            background-color: #5cb85c;
            width: 100%;
            text-shadow: 0 -1px 0 rgba(0,0,0,.2);
            -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.15),0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.15),0 1px 1px rgba(0,0,0,.075);
            background-image: -webkit-linear-gradient(top,#5cb85c 0,#419641 100%);
            background-image: -o-linear-gradient(top,#5cb85c 0,#419641 100%);
            background-image: -webkit-gradient(linear,left top,left bottom,from(#5cb85c),to(#419641));
            background-image: linear-gradient(to bottom,#5cb85c 0,#419641 100%);
            background-repeat: repeat-x;
            border-color: #3e8f3e;
        }
        .apply-btn:hover {
            cursor: hand;
        }
        .pad-left {
            padding-left: 20px;
        }
    </style>
</head>
<header>
    <div class="top-space"></div>
    <div class="container">
        <table>
            <tr>
                <td class="size-4 pad-left"><img src="https://checkout-test.paybreak.com/assets/logo-mobile-no-with.png"></td>
                <td class="size-8"><img src="{{$installation_logo}}" class="display-right retailer-logo"></td>
            </tr>
        </table>
    </div>
</header>
<body class="container">
<hr class="top-break">
<div class="row">
    <div class="wrapper">
        <table>
            <thead>
                <tr>
                    <td colspan="2" class="introduction-text">
                        Dear {{$customer_title}} {{$customer_last_name}}, <br/>
                        You are receiving this email in result to an enquiry you made with <strong>{{$installation_name}}</strong>.
                    </td>
                </tr>
            </thead>
        </table>
        <br/>
        <table class="size size-4">
            <tbody>
                <tr><td colspan="2"><h3>How it works</h3></td></tr>
                <tr>
                    <td>
                        <p>If everything looks OK and you wish to apply for finance, click the button below and your browser will open the finance application form</p>
                        <p>Complete the form - it will only take a few minutes</p>
                        <p>You sign the credit agreement online, so there are no paper forms and nothing to post</p>
                        <p>A decision will be made based on the criteria entered</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="size size-8">
            <tbody>
                <tr><td colspan="2"><h3>Your Finance Offer</h3></td></tr>
                <tr>
                    <td class="size-4 text-right">Product:</td>
                    <td class="size-8"><strong>{{$order_description}}</strong></td>
                </tr>
                <tr>
                    <td class="size-4 text-right">Monthly Payment:</td>
                    <td class="size-8">{{\'&pound;\' . number_format($payment_regular/100,2)}} for {{$payments}} months</td>
                </tr>
                <tr>
                    <td class="size-4 text-right">Finance Details:</td>
                    <td class="size-8">{{$order_description}}</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">Purchase Price: {{\'&pound;\' . number_format($order_amount/100,2)}}</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">Deposit: {{\'&pound;\' . number_format($deposit_amount/100,2)}}</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">Loan Amount: {{\'&pound;\' . number_format($loan_amount/100,2)}}</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">{{$payments}} Monthly payments of: {{\'&pound;\' . number_format($payment_regular/100,2)}}</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">Total Amount Repayable: {{\'&pound;\' . number_format($total_repayment/100,2)}}</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">Rate of Interest (fixed): {{number_format($offered_rate, 2)}}%</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">{{number_format($apr, 2)}}% APR Representative</td>
                </tr>
                <tr>
                    <td class="size-4 text-right"></td>
                    <td class="size-8">Total Charge for Credit: {{\'&pound;\' . number_format($loan_cost/100,2)}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="wrapper">
        <div class="size-4">
            <a href="{{$apply_url}}"><button class="apply-btn">Apply Now</button></a>
        </div>
        <div class="col col-sm-12 col-xs-12">
            {!! html_entity_decode($template_footer) !!}
        </div>
    </div>
</div>
<hr class="top-break">
</body>
</html>';
        $template->merchant_id = null;
        $template->save();

        $installations = \App\Basket\Installation::all();

        /** @var \App\Basket\Installation $installation */
        foreach($installations as $installation) {
            $installation->templates()->attach($template);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Should be removed manually if the single migration is being reverted
        // If all are being reverted then the table will be dropped and the template will be removed automatically
    }
}

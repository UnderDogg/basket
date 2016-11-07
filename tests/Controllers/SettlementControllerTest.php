<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Http\Controllers;
use App\Merchants;

class SettlementControllerTest extends TestCase
{
     private $settlementReport;
     private $settlementControllerObject;

    /**
     * @author EA
     */
    public function setUp()
    {
        parent::setUp();
        $settlementControllerClass = new ReflectionClass('App\Http\Controllers\SettlementsController');
        $this->settlementControllerObject = $settlementControllerClass->newInstanceWithoutConstructor();

        $this->settlementReport = [
            'settlements' =>
                [
                    [
                        'id' => 2,
                        'application' => 1000000073,
                        'received_date' => '2016-05-31T10:40:04+01:00',
                        'loan_amount' => 54000,
                        'order_amount' => 60000,
                        'deposit' => 6000,
                        'description' => 'Order from TestCheckout',
                        'customer_name' => 'Mr David Cameron',
                        'application_postcode' => 'SW1A 2AA',
                        'order_reference' => '86124bb7-57e6-0d91-075d',
                        'captured_date' => '2016-06-05',
                        'fulfilment_date' => '1970-01-01T01:00:00+01:00',
                        'settlement_report' => 2,
                        'transactions' =>
                            [
                                [
                                    'type' => 'Fulfilment',
                                    'amount' => 60000,
                                ],
                                [
                                    'type' => 'Merchant Commission',
                                    'amount' => 2000,
                                ],
                                [
                                    'type' => 'Merchant Fee Charged',
                                    'amount' => -3000,
                                ],
                                [
                                    'type' => 'Manual Adjustment',
                                    'amount' => 1000,
                                ],
                            ],
                        ],
                    [
                        'id' => 3,
                        'application' => 1000000073,
                        'received_date' => '2016-05-31T10:40:04+01:00',
                        'loan_amount' => 54000,
                        'order_amount' => 60000,
                        'deposit' => 6000,
                        'description' => 'Order from TestCheckout',
                        'customer_name' => 'Mr David Cameron',
                        'application_postcode' => 'SW1A 2AA',
                        'order_reference' => '86124bb7-57e6-0d91-075d',
                        'captured_date' => '2016-06-05',
                        'fulfilment_date' => '1970-01-01T01:00:00+01:00',
                        'settlement_report' => 2,
                        'transactions' =>
                            [
                                [
                                    'type' => 'Partial Refund',
                                    'amount' => -2000,
                                ],
                                [
                                    'type' => 'Manual Adjustment',
                                    'amount' => 1000,
                                ],
                            ],
                    ],
                    [
                        'id' => 1,
                        'application' => 1000000073,
                        'received_date' => '2016-05-31T10:40:04+01:00',
                        'loan_amount' => 54000,
                        'order_amount' => 60000,
                        'deposit' => 6000,
                        'description' => 'Order from TestCheckout',
                        'customer_name' => 'Mr David Cameron',
                        'application_postcode' => 'SW1A 2AA',
                        'order_reference' => '86124bb7-57e6-0d91-075d',
                        'captured_date' => '2016-06-06',
                        'fulfilment_date' => '1970-01-01T01:00:00+01:00',
                        'settlement_report' => 2,
                        'transactions' =>
                            [
                                [
                                    'type' => 'Cancellation Fee',
                                    'amount' => -900,
                                ],
                                [
                                    'type' => 'Refund',
                                    'amount' => -60000,
                                ],
                                [
                                    'type' => 'Merchant Commission Refunded',
                                    'amount' => -2000,
                                ],
                                [
                                    'type' => 'Merchant Fee Refunded',
                                    'amount' => 3000,
                                ],
                                [
                                    'type' => 'Reversal Of Partial Refund',
                                    'amount' => 2000,
                                ],
                                [
                                    'type' => 'Manual Adjustment',
                                    'amount' => -2000,
                                ],
                            ],
                     ],
                    ],
                ];
    }


    /**
     * @author EA
     */
    public function testFlattenRawReport()
    {
        $lastRow = [
            'Order Date' => '31/05/2016',
            'Customer' => 'Mr David Cameron',
            'Post Code' => 'SW1A 2AA',
            'Retailer Reference' => '86124bb7-57e6-0d91-075d',
            'Order Amount' => '600.00',
            'Notification Date' => '06/06/2016',
            'Type' => 'Manual Adjustment',
            'Description' => 'Order from TestCheckout',
            'Deposit' => '60.00',
            'Settlement Amount'=> '-20.00',
        ];

        $method = new ReflectionMethod('App\Http\Controllers\SettlementsController', 'flattenRawReport');
        $method->setAccessible(true);
        $flattenSettlementReport = $method->invokeArgs($this->settlementControllerObject, [$this->settlementReport]);

        $this->assertArraySubset($lastRow, $flattenSettlementReport[11]);
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author EB
 * Class BitwiseFixturesForFinanceOffers
 */
class BitwiseFixturesForFinanceOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $installations = \App\Basket\Installation::all();

        /** @var \App\Basket\Installation $installation */
        foreach ($installations as $installation) {
            $bitwise = \PayBreak\Foundation\Properties\Bitwise::make($installation->finance_offers);

            if (
                $bitwise->contains(\App\Basket\Installation::LINK) ||
                $bitwise->contains(\App\Basket\Installation::EMAIL)
            ) {
                $bitwise->remove(\App\Basket\Installation::LINK);
                $bitwise->remove(\App\Basket\Installation::EMAIL);
                $total = $bitwise->get() + (\App\Basket\Installation::LINK + \App\Basket\Installation::EMAIL);
                $installation->finance_offers = $total;
                $installation->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

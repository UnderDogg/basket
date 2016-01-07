<?php

use Illuminate\Database\Eloquent\Model;

class MerchantTableSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::insert('INSERT INTO merchants (id, name, token, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
            2,
            'The Perfect Online Store',
            'perfecttoken',
            time(),
            time(),
        ]);

        Model::reguard();
    }
}

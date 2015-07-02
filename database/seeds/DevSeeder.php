<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DevSeeder extends Seeder
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
            1,
            'Test Merchant',
            'mytoken',
            time(),
            time(),
        ]);

        $user = new \App\User();
        $user->name = 'Dev';
        $user->email = 'dev@paybreak.com';
        $user->password = bcrypt('password');
        $user->merchant_id = 1;
        $user->save();

        $reportRole = \App\Role::where('name', '=', 'report')->first();
        $user->attachRole($reportRole);

        $instoreRole = \App\Role::where('name', '=', 'instore')->first();
        $user->attachRole($instoreRole);
    }
}

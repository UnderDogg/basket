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

        $user = new \App\User();
        $user->name = 'Dev';
        $user->email = 'dev@paybreak.com';
        $user->password = bcrypt('password');
        $user->save();

        $reportRole = \App\Role::where('name', '=', 'report')->first();
        $user->attachRole($reportRole);

        $instoreRole = \App\Role::where('name', '=', 'instore')->first();
        $user->attachRole($instoreRole);
    }
}

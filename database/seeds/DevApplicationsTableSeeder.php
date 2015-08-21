<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DevApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(DevSeeder::class);
        factory(App\Basket\Application::class, 50)->make();

        Model::reguard();
    }
}

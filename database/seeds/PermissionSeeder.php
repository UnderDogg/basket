<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Permission;
use App\User;

class DBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author MS
     * @return void
     */
    public function run()
    {
        $this->seedDataSource();
    }

    /**
     * Seed Data Source
     *
     * @author MS
     * @return void
     */
    protected function seedDataSource()
    {
        Model::unguard();



        Model::reguard();
    }
}

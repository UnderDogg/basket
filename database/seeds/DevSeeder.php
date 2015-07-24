<?php

use Illuminate\Database\Eloquent\Model;
use App\Basket\Installation;
use App\Role;
use App\Permission;
use App\RolePermissions;

class DevSeeder extends DBSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        parent::applySeederData();

        $this->roles[2] = ['Report Role', 'report', 'run reports'];
        $this->roles[3] = ['Manager Role', 'manager', 'run reports and perform cancellations'];
        $this->roles[4] = ['In-Store Role', 'instore', 'access in-store finance page and in-store details'];

        $this->rolesPermissions[2] = [1, 2];
        $this->rolesPermissions[3] = [1, 2, 3];
        $this->rolesPermissions[4] = [4, 5];

        $this->users[2] = ['Dev Reporter', 'report@paybreak.com', 'password', 1, 1];
        $this->users[3] = ['Dev Manager', 'manager@paybreak.com', 'password', 1, 2];
        $this->users[4] = ['Dev Sales', 'sales@paybreak.com', 'password', 1, 3];

        DB::insert('INSERT INTO merchants (id, name, token, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
            1,
            'Test Merchant',
            'mytoken',
            time(),
            time(),
        ]);

        parent::seedDataSource();


    }
}

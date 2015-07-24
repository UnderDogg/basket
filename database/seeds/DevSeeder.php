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

        $this->roles[] = ['Report Role', 'report', 'run reports'];
        $this->roles[] = ['Manager Role', 'manager', 'run reports and perform cancellations'];
        $this->roles[] = ['In-Store Role', 'instore', 'access in-store finance page and in-store details'];

        $this->rolesPermissions[] = [1, 2];
        $this->rolesPermissions[] = [1, 2, 3];
        $this->rolesPermissions[] = [4, 5];

        $this->users[] = ['Dev Reporter', 'report@paybreak.com', 'password', 1, 1];
        $this->users[] = ['Dev Manager', 'manager@paybreak.com', 'password', 1, 2];
        $this->users[] = ['Dev Sales', 'sales@paybreak.com', 'password', 1, 3];

        DB::insert('INSERT INTO merchants (id, name, token, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
            1,
            'Test Merchant',
            'mytoken',
            time(),
            time(),
        ]);

        parent::seedDataSource();

        Model::reguard();
    }
}

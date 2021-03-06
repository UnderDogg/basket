<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Permission;
use App\User;

/**
 * Class PermissionSeeder
 *
 * @author SL
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author SL
     * @return void
     */
    public function run()
    {
        $this->seedDataSource();
    }

    /**
     * Seed Data Source
     *
     * @author SL
     * @return void
     */
    protected function seedDataSource()
    {
        $permission = new \App\Permission();
        $permission->name = 'applications-merchant-payments';
        $permission->display_name = 'Merchant Payments';
        $permission->description = 'merchant payments';
        $permission->save();

        $roles = [
            'manager',
            'su',
            'administrator'
        ];

        foreach ($roles as $roleName) {
            try {
                $role = \App\Role::where('name', '=', $roleName)->first();

                $permission->roles()->attach($role->id);
            } catch (Exception $e) {
            }
        }

        $permission->save();
    }
}

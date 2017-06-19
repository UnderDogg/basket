<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;

class SalesTeamLeadRole extends Seeder
{
    const EXISTING_SALES_ROLE_KEY = 'sales';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Role $newRole */
        $newRole = Role::where('name', self::EXISTING_SALES_ROLE_KEY)->first();
        $newRole->name = 'sales-lead';
        $newRole->display_name = 'Sales Team Lead Role';
        $newRole->description = 'sales team lead, access in-store finance page and in-store details';
        $newRole->save();

        $oldRole = new Role();
        $oldRole->name = self::EXISTING_SALES_ROLE_KEY;
        $oldRole->display_name = 'Sales Role';
        $oldRole->description = 'access in-store finance page and in-store details';
        $oldRole->save();

        $oldRole->permissions()->sync($newRole->permissions, false);

        $newRole->permissions()->attach($this->makeApplicationViewAll());
    }

    private function makeApplicationViewAll()
    {
        $permission = new Permission();
        $permission->name = Permission::VIEW_ALL_APPLICATIONS;
        $permission->display_name = 'Applications View All';
        $permission->description = 'can view applications made by all users';
        $permission->save();

        return $permission;
    }
}

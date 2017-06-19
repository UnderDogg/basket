<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;

class SalesTeamLeadRole extends Seeder
{
    const EXISTING_SALES_ROLE_KEY = 'sale';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = $this->makeSalesLeadPermission();

        $existingSalesRole = Role::where('name', self::EXISTING_SALES_ROLE_KEY)->first();
        $newRole = $this->clonePermissionsFromRoleWithAdditionalPermission($existingSalesRole, $permission);

        /** @var User $user */
        foreach ($existingSalesRole->users as $user) {
            $user->roles()->attach($newRole);
        }

        /** @var Role $role */
        foreach (Role::where('name', 'su')->get() as $role) {
            $role->permissions()->attach($permission);
        }

        /** @var Role $role */
        foreach (Role::where('name', 'rosu')->get() as $role) {
            $role->permissions()->attach($permission);
        }
    }

    private function makeSalesLeadPermission()
    {
        $permission = new Permission();
        $permission->name = Permission::VIEW_ALL_APPLICATIONS;
        $permission->display_name = 'Applications View All';
        $permission->description = 'can view applications made by all users';
        $permission->save();

        return $permission;
    }

    /**
     * @param Role $sourceRole
     * @return Role
     */
    private function clonePermissionsFromRoleWithAdditionalPermission(Role $sourceRole, Permission $permission)
    {
        $role = new Role();
        $role->name = 'sales-lead';
        $role->display_name = 'Sales Team Lead Role';
        $role->description = 'sales team lead';
        $role->save();

        $role->permissions()->attach($permission);

        //Clone the permissions from another role
        foreach ($sourceRole->permissions as $permission) {
            $role->permissions()->attach($permission);
        }

        return $role;
    }
}

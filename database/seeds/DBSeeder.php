<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Permission;
use App\RolePermissions;

class DBSeeder extends Seeder
{
    /** @var  array $permissions */
    protected $permissions;

    /** @var  array $roles */
    protected $roles;

    /** @var  array $rolesPermissions */
    protected $rolesPermissions;

    /**
     * Apply Seeder Data
     *
     * @author MS
     * @return void
     */
    protected function applySeederData()
    {
        /*
         * PERMISSIONS   |  Nice Name    |  Name     |  Description
         */
        $this->permissions[1] = ['Read Applications', 'read-applications', 'read applications'];
        $this->permissions[2] = ['Read Settlements', 'read-settlements', 'read settlements'];
        $this->permissions[3] = ['Cancel Applications', 'cancel-applications', 'cancel applications'];
        $this->permissions[4] = ['Access In-Store Finance Page', 'access-in-store-finance-page', 'access in-store finance page'];
        $this->permissions[5] = ['Access In-Store Details', 'access-in-store-details', 'access in-store details'];


        /*
         * ROLES         |  Nice Name    |  Name     |  Description
         */
        $this->roles[1] = ['Report Role', 'role', 'run reports'];
        $this->roles[2] = ['Manager Role', 'manager', 'run reports and perform cancellations'];
        $this->roles[3] = ['In-Store Role', 'instore', 'access in-store finance page and in-store details'];

        /*
         * PERMISSIONS FOR ROLE OF ID...
         */
        $this->rolesPermissions[1] = [1, 2];
        $this->rolesPermissions[2] = [1, 2, 3];
        $this->rolesPermissions[3] = [4, 5];
    }

    /**
     * Run the database seeds.
     *
     * @author MS
     * @return void
     */
    public function run()
    {
        $this->applySeederData();
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

        foreach ($this->permissions as $permissionsToAdd) {
            $permissionObject = new Permission();
            $permissionObject->name = $permissionsToAdd[1];
            $permissionObject->display_name = $permissionsToAdd[0];
            $permissionObject->description  = $permissionsToAdd[2];
            $permissionObject->save();
        }

        foreach ($this->roles as $rolesToAdd) {
            $roleObject = new Role();
            $roleObject->name = $rolesToAdd[1];
            $roleObject->display_name = $rolesToAdd[0];
            $roleObject->description  = $rolesToAdd[2];
            $roleObject->save();
        }

        foreach ($this->rolesPermissions as $role => $permissionsToAdd) {
            foreach ($permissionsToAdd as $permission) {
                $rolePermissionObject = new RolePermissions();
                $rolePermissionObject->role_id = $role;
                $rolePermissionObject->permission_id = $permission;
                $rolePermissionObject->save();
            }
        }

        Model::reguard();
    }
}
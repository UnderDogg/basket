<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Permission;
use App\User;

class DBSeeder extends Seeder
{
    /** @var  array $permissions */
    protected $permissions = [];

    /** @var  array $roles */
    protected $roles = [];

    /** @var  array $rolesPermissions */
    protected $rolesPermissions = [];

    /** @var  array $users */
    protected $users = [];

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
        $this->permissions[1] = ['Merchants Management', 'merchants-management', 'merchants management'];
        $this->permissions[2] = ['Merchants View', 'merchants-view', 'merchants show'];

        $this->permissions[3] = ['Users Management', 'users-management', 'user management'];
        $this->permissions[4] = ['Users View', 'users-view', 'user view'];

        $this->permissions[5] = ['Roles Management', 'roles-management', 'roles management'];
        $this->permissions[6] = ['Roles View', 'roles-view', 'roles view'];

        $this->permissions[7] = ['Locations View', 'locations-view', 'locations view'];
        $this->permissions[8] = ['Locations Management', 'locations-management', 'locations management'];

        $this->permissions[9] = ['Applications View', 'applications-view', 'applications view'];
        $this->permissions[10] = ['Applications Make', 'applications-make', 'applications make'];
        $this->permissions[11] = ['Applications Fulfil', 'applications-fulfil', 'applications fulfil'];
        $this->permissions[12] = ['Applications Cancel', 'applications-cancel', 'applications cancel'];
        $this->permissions[13] = ['Applications Refund', 'applications-refund', 'applications refund'];

        $this->permissions[14] = ['Reports View', 'reports-view', 'reports-view'];

        /*
         * ROLES         |  Nice Name    |  Name     |  Description
         */
        $this->roles[] = ['System Administrator', 'su', 'Can do everything'];

        /*
         * PERMISSIONS FOR ROLE
         * RoleID = array PermissionsID
         */
        $this->rolesPermissions[] = array_keys($this->permissions);

        /*
         * USERS         |  Name        |  Email     |  Password     |  Merchant ID   |  Role ID
         */
        $this->users[] = ['Administrator', 'dev@paybreak.com', 'password', null, 1];
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

        $roles = [];

        foreach ($this->roles as $rolesToAdd) {
            $roleObject = new Role();
            $roleObject->name = $rolesToAdd[1];
            $roleObject->display_name = $rolesToAdd[0];
            $roleObject->description  = $rolesToAdd[2];
            $roleObject->save();
            $roles[] = $roleObject;
        }

        foreach ($this->rolesPermissions as $role => $permissionsToAdd) {
            foreach ($permissionsToAdd as $permission) {
                $role1 = Role::findOrFail($role + 1);
                $role1->permissions()->attach($permission);
            }
        }

        foreach ($this->users as $user) {
            $userObject= new User();
            $userObject->name = $user[0];
            $userObject->email = $user[1];
            $userObject->password = bcrypt($user[2]);
            $userObject->merchant_id = $user[3];
            $userObject->role_id = $user[4];
            $userObject->save();

            $userObject->attachRole($roles[$user[4]-1]);
        }

        Model::reguard();
    }
}

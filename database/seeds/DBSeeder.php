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
        $this->permissions[] = ['Merchants Management', 'merchants-management', 'merchants management'];
        $this->permissions[] = ['Roles Management', 'roles-management', 'roles management'];
        $this->permissions[] = ['User Management', 'user-management', 'user management'];
        $this->permissions[] = ['Merchant Management', 'merchant-management', 'merchant management'];
        $this->permissions[] = ['Read Applications', 'read-applications', 'read applications'];
        $this->permissions[] = ['Read Settlements', 'read-settlements', 'read settlements'];
        $this->permissions[] = ['Cancel Applications', 'cancel-applications', 'cancel applications'];
        $this->permissions[] = ['Access In-Store Finance Page', 'access-in-store-finance-page', 'access in-store finance page'];
        $this->permissions[] = ['Access In-Store Details', 'access-in-store-details', 'access in-store details'];

        /*
         * ROLES         |  Nice Name    |  Name     |  Description
         */
        $this->roles[] = ['Super User', 'su', 'Can do everything'];

        /*
         * PERMISSIONS FOR ROLE
         * RoleID = array PermissionsID
         */
        $this->rolesPermissions[] = [1, 2, 3];

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

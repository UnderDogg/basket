<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Permission;
use App\User;

/**
 * Class ReadOnlySuperUserSeeder
 *
 * @author EB
 */
class ReadOnlySuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author EB
     * @return void
     */
    public function run()
    {
        $this->seedDataSource();
    }

    /**
     * Seed Data Source
     *
     * @author EB
     * @return void
     */
    protected function seedDataSource()
    {
        Model::unguard();

        $role = new \App\Role();
        $role->name = 'rosu';
        $role->display_name = 'Read Only Super User';
        $role->description = 'Read only user who can see everything';
        $role->save();

        $permissions = [
            'merchants-view',
            'users-view',
            'roles-view',
            'locations-view',
            'applications-view',
            'reports-view',
        ];

        foreach ($permissions as $permission) {
            try {
                $perm = \App\Permission::where('name', '=', $permission)->first();
                $role->permissions()->sync([$perm->id], false);
            } catch (Exception $e) {
            }
        }

        Model::reguard();
    }
}

<?php

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ReportExporterRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // create new role
        $role = new \App\Role();
        $role->name = 'report-exporter';
        $role->display_name = 'Report Exporter';
        $role->description = 'Can download reports';
        $role->save();

        // create new permission
        $permission = new \App\Permission();
        $permission->name = 'download-reports';
        $permission->display_name = 'Download Reports';
        $permission->description = 'Allows the role to download reports';
        $permission->save();

        // attach permission to role
        $permission->roles()->attach($role->id);
        $permission->save();

        //add permission to all users
        $users = User::all();
        foreach ($users as $user) {
            $user->attachRole($role);
        }

        Model::reguard();
    }
}

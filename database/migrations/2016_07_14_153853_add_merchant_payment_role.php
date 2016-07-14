<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class Add Merchant Payment Role
 *
 * @author SL
 */
class AddMerchantPaymentRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = new \App\Permission();
        $permission->name = 'merchant-payments';
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        $permission = \App\Permission::where('name', '=', 'merchant-payments')->first();
//
//        $roles = [
//            'manager',
//            'su',
//            'administrator'
//        ];
//
//        foreach ($roles as $roleName) {
//
//            $role = \App\Role::where('name', '=', $roleName)->first();
//
//            $permission->roles()->detach($role->id);
//        }
//
//        $permission->save();
//
//        $permission->delete();
    }
}

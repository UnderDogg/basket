<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

/**
 * Class addTestUsers
 *
 * @author GK
 */
class DevUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author GK
     * @return void
     */
    public function run()
    {
        /** @var User $salesLeader */
        $salesLeader = User::find(5);
        $salesLeader->name = 'Dev Sales Leader';
        $salesLeader->email = 'sales-lead@paybreak.com';
        $salesLeader->save();

        $users[] = ['Dev Sales', 'sales@paybreak.com', 'password', 1, 6];
        $users[] = ['Dev Read-only Super User', 'rosu@paybreak.com', 'password', 1, 7];

        foreach ($users as $user) {
            $this->addUser($user);
        }

        echo ' - Test users added' . "\r\n";
    }

    /**
     * @author GK
     * @param $user
     * @return bool
     */
    private function addUser($user)
    {
        $userObject= new User();
        $userObject->name = $user[0];
        $userObject->email = $user[1];
        $userObject->password = bcrypt($user[2]);
        $userObject->merchant_id = $user[3];
        $userObject->role_id = $user[4];
        $userObject->save();

        $role = Role::find($user[4]);
        $userObject->attachRole($role);

        return true;
    }
}

<?php

use Illuminate\Database\Eloquent\Model;
use App\Basket\Installation;
use App\Role;
use App\Permission;
use App\Basket\Location;
use App\Basket\Merchant;

class DevSeeder extends DBSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Apply Parents (LIVE) details first
        parent::applySeederData();

        // SET INSTALLATIONS        | merchant_ID | name | bool active | bool linked
        $installations[] = [1, 'Test Installation', 1, 1, 'TestInstall'];
        $installations[] = [1, 'Unlinked Installation', 0, 0, ''];

        // SET LOCATIONS            | reference | installation_id | bool active | name | email | address
        $locations[] = ['HIGHLOC', 1, 1, 'Higher Location', 'kira@highloc.com', 'Higher Location City'];

        $this->roles[] = ['Merchant Administrator', 'administrator', 'Merchant Administrator'];
        $this->roles[] = ['Report Role', 'report', 'run reports'];
        $this->roles[] = ['Manager Role', 'manager', 'run reports and perform cancellations'];
        $this->roles[] = ['Sales Role', 'sale', 'access in-store finance page and in-store details'];

        $this->rolesPermissions[] = [1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $this->rolesPermissions[] = [7, 9, 14];
        $this->rolesPermissions[] = [7, 9, 10, 11, 12, 13];
        $this->rolesPermissions[] = [7, 9];

        $this->users[] = ['Dev Merchant Administrator', 'it@paybreak.com', 'password', 1, 2];
        $this->users[] = ['Dev Reporter', 'report@paybreak.com', 'password', 1, 3];
        $this->users[] = ['Dev Manager', 'manager@paybreak.com', 'password', 1, 4];
        $this->users[] = ['Dev Sales', 'sales@paybreak.com', 'password', 1, 5];


        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->active = 1;
        $merchant->name = 'Test Merchant';
        $merchant->token = 'mytoken';
        $merchant->save();

        // Apply Seed Data to Data Source
        parent::seedDataSource();

        // INSTALLATIONS
        foreach ($installations as $installation) {
            $installationObject = new Installation();
            $installationObject->merchant_id = $installation[0];
            $installationObject->name = $installation[1];
            $installationObject->active = $installation[2];
            $installationObject->linked = $installation[3];
            $installationObject->ext_id = $installation[4];
            $installationObject->location_instruction = '';
            $installationObject->merchant_payments = 0;
            $installationObject->finance_offers = 14;
            $installationObject->save();
        }

        // LOCATIONS
        foreach ($locations as $location) {
            $locationObject = new Location();
            $locationObject->reference = $location[0];
            $locationObject->installation_id = $location[1];
            $locationObject->active = $location[2];
            $locationObject->name = $location[3];
            $locationObject->email = $location[4];
            $locationObject->address = $location[5];
            $locationObject->save();
        }

        Model::reguard();
    }
}

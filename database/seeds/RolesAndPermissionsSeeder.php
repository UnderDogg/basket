<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // permissions

        $readApplications = new Permission();
        $readApplications->name = 'read-applications';
        $readApplications->display_name = 'Read Applications';
        $readApplications->description  = /* allow a user to... */ 'read applications';
        $readApplications->save();

        $readSettlements = new Permission();
        $readSettlements->name = 'read-settlements';
        $readSettlements->display_name = 'Read Settlements';
        $readSettlements->description  = /* allow a user to... */ 'read settlements';
        $readSettlements->save();

        $cancelApplications = new Permission();
        $cancelApplications->name = 'cancel-applications';
        $cancelApplications->display_name = 'Cancel Applications';
        $cancelApplications->description  = /* allow a user to... */ 'cancel applications';
        $cancelApplications->save();

        $accessInStoreFinancePage = new Permission();
        $accessInStoreFinancePage->name = 'access-instore-finance-page';
        $accessInStoreFinancePage->display_name = 'Access In-Store Finance Page';
        $accessInStoreFinancePage->description  = /* allow a user to... */ 'access the in-store finance page';
        $accessInStoreFinancePage->save();

        $accessInStoreDetails = new Permission();
        $accessInStoreDetails->name = 'access-instore-details';
        $accessInStoreDetails->display_name = 'Access In-Store Details';
        $accessInStoreDetails->description  = /* allow a user to... */ 'access in-store details';
        $accessInStoreDetails->save();

        // roles

        $report = new Role();
        $report->name = 'report';
        $report->display_name = 'Report Role';
        $report->description  = 'User can run reports';
        $report->save();

        $report->attachPermissions(
            [
                $readApplications,
                $readSettlements,
            ]
        );

        $manager = new Role();
        $manager->name = 'manager';
        $manager->display_name = 'Manager Role';
        $manager->description  = 'User can run reports and perform cancellations';
        $manager->save();

        $manager->attachPermissions(
            [
                $readApplications,
                $readSettlements,
                $cancelApplications,
            ]
        );

        $instore = new Role();
        $instore->name = 'instore';
        $instore->display_name = 'In-Store Role';
        $instore->description  = 'User can access in-store finance page and in-store details';
        $instore->save();

        $instore->attachPermissions(
            [
                $accessInStoreFinancePage,
                $accessInStoreDetails,
            ]
        );
    }
}

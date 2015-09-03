<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Basket\Application::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 3),
        'installation_id' => 1,
        'location_id' => 1,
        'ext_id' => $faker->unique()->numberBetween(1000),
        'ext_current_status' => 'converted',
        'ext_order_reference' => str_random(16),
        'ext_order_amount' => $faker->numberBetween($min = 1000, $max = 10000),
        'ext_order_description' => $faker->company,
        'ext_order_validity' => '',
        'ext_products_options' => '',
        'ext_products_groups' => '',
        'ext_products_default' => '',
        'ext_fulfilment_method' => '',
        'ext_fulfilment_location' => $faker->text(10),
        'ext_customer_title' => $faker->title,
        'ext_customer_first_name' => $faker->name,
        'ext_customer_last_name' => $faker->lastName,
        'ext_customer_email_address'=> $faker->email,
        'ext_customer_phone_home' => $faker->phoneNumber,
        'ext_customer_phone_mobile' => $faker->phoneNumber,
        'ext_customer_postcode' => $faker->postcode,
        'ext_application_address_abode' => $faker->buildingNumber,
        'ext_application_address_building_name' => '',
        'ext_application_address_building_number' => $faker->buildingNumber,
        'ext_application_address_street' => $faker->streetName,
        'ext_application_address_locality' => $faker->streetName,
        'ext_application_address_town' => $faker->city,
        'ext_application_address_postcode' => $faker->postcode,
        'ext_applicant_title' => $faker->title,
        'ext_applicant_first_name' => $faker->name,
        'ext_applicant_last_name' => $faker->lastName,
        'ext_applicant_date_of_birth' => $faker->dateTime,
        'ext_applicant_email_address' => $faker->email,
        'ext_applicant_phone_home' => $faker->phoneNumber,
        'ext_applicant_phone_mobile' => $faker->phoneNumber,
        'ext_applicant_postcode' => $faker->postcode,
        'ext_finance_order_amount' => $faker->numberBetween($min = 1000, $max = 10000),
        'ext_finance_loan_amount' => $faker->numberBetween($min = 1000, $max = 10000),
        'ext_finance_deposit' => $faker->numberBetween($min = 1000, $max = 2000),
        'ext_finance_subsidy' => $faker->numberBetween($min = 1000, $max = 2000),
        'ext_finance_net_settlement' => $faker->numberBetween($min = 1000, $max = 5000),
        'ext_metadata' => '',
        'last_sync_at' => $faker->dateTime,
    ];
});

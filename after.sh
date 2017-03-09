#!/bin/sh
echo ""
echo "============================================"
echo " Homestead - PROVISIONING"
echo "============================================"

cd /var/www/basket/
composer install

echo "# Migrations"
php artisan migrate
echo "# Seeders"
php artisan db:seed --class=DevSeeder
php artisan db:seed --class=ReportExporterRoleSeeder

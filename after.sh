#!/bin/sh
echo ""
echo "============================================"
echo " Homestead - PROVISIONING"
echo "============================================"

echo "# Migrations"
cd /var/www/basket/
php artisan migrate
echo "# Seeders"
php artisan db:seed --class=DevSeeder

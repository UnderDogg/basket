#!/bin/sh
echo ""
echo "============================================"
echo " Homestead - PROVISIONING"
echo "============================================"

cd /var/www/basket/
php artisan migrate

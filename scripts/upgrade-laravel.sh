#!/usr/bin/env bash

sudo rm -rf vendor
/usr/local/bin/composer.phar install --ignore-platform-reqs
/usr/local/bin/composer.phar du
php artisan clear-compiled
php artisan route:clear
cp .env.example .env
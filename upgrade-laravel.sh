#!/usr/bin/env bash

sudo rm -rf vendor
composer install --ignore-platform-reqs
composer du
php artisan clear-compiled
php artisan route:clear
cp .env.example .env

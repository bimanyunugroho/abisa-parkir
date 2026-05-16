#!/bin/sh
set -e

echo "======================================"
echo "  SiParkir Production Start"
echo "======================================"

echo "--> Caching config..."
php artisan config:cache

echo "--> Caching routes..."
php artisan route:cache

echo "--> Caching views..."
php artisan view:cache

echo "--> Running migrations..."
php artisan migrate --force

echo "--> Linking storage..."
php artisan storage:link || true

echo "--> Starting PHP-FPM..."
php-fpm -D

echo "--> Starting Nginx..."
nginx -g "daemon off;"
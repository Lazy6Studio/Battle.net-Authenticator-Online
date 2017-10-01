#!/bin/sh
nginxFile="/etc/nginx/nginx.conf"
if [ -e "$nginxFile" ]; then
    \cp -f public/.htaccessForNginx public/.htaccess
else
    \cp -f public/.htaccessForApache public/.htaccess
fi
\mv -f .env.production .env
php artisan cache:clear
php artisan config:clear
composer install
chmod -R 777 storage
chmod -R 777 bootstrap/cache
chmod -R 777 public/temp

output=`php install.php`
if [ "$output" = "" ]; then
    echo "Db insert success."
    rm database.sql
fi
rm install.php
rm install.sh
rm installWithoutDbInsert.sh
echo "Install finished."
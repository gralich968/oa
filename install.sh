#!/bin/bash

php artisan down
composer update -W
sudo find . -type f -exec chmod 644 {} \;
sudo chmod u+x install.sh
sudo find . -type d -exec chmod 755 {} \;
sudo chmod -R 777 ./storage
sudo chmod -R 777 ./bootstrap/cache/
php artisan storage:link
php artisan optimize
php artisan up




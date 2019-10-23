#!/bin/bash

# Set permissions to storage and bootstrap cache

cd /var/www/html

sudo chmod -R 0777 storage
sudo chmod -R 0777 bootstrap/cache

# Install composer dependencies
composer install

# Start server
service apache2 restart
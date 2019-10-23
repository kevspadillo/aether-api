#!/bin/bash

# Set permissions to storage and bootstrap cache

cd /var/www/html

chmod -R 0777 storage
chmod -R 0777 bootstrap/cache

# Install composer dependencies
composer install

# Start server
service apache2 restart
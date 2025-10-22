#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

# echo "generating application key..."
# php artisan key:generate --show

# echo "Setting permissions..."
# chmod -R 775 storage bootstrap/cache

# echo "who am i?"
# whoami

echo "clearing cache..."
php artisan cache:clear

echo "Clear all optimized files..."
php artisan optimize:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Setting permissions..."
# Fixing permissions for storage and cache directories
chown -R nginx:nginx /var/www/html/storage
chown -R nginx:nginx /var/www/html/bootstrap/cache

# Set correct file and directory permissions
find /var/www/html/storage -type d -exec chmod 775 {} \;
find /var/www/html/storage -type f -exec chmod 664 {} \;
find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \;
find /var/www/html/bootstrap/cache -type f -exec chmod 664 {} \;

echo "Linking storage..."
php artisan storage:link

echo "Creating Permissions..."
php artisan permission:create-permission-routes

# echo "Running seeder files..."
# php artisan db:seed --force

echo "Running ..."
ls -l /var/www/html/storage
ls -l /var/www/html/bootstrap/cache

echo "Running composer dump-autoload"
composer dump-autoload

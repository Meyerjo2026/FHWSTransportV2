#!/bin/sh
set -e

echo "Creating Nginx temp directories..."
mkdir -p /tmp/nginx/client_body /tmp/nginx/proxy /tmp/nginx/fastcgi /tmp/nginx/uwsgi /tmp/nginx/scgi
chmod 755 /tmp/nginx /tmp/nginx/*

echo "Ensuring storage permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Force LOG_CHANNEL=stderr so Laravel never tries to write a log file.
# This overrides whatever is cached or set in env — no file = no permission error.
export LOG_CHANNEL=stderr

echo "Clearing stale config cache (will re-cache with correct env)..."
php artisan config:clear 2>&1 || true

echo "Waiting for database..."
tries=0
until php artisan db:show > /dev/null 2>&1 || [ "$tries" -ge 30 ]; do
    tries=$((tries + 1))
    sleep 2
done

if [ "$tries" -ge 30 ]; then
    echo "Database did not become reachable in time; continuing anyway."
fi

echo "Running migrations..."
php artisan migrate --force 2>&1 | tail -20

echo "Caching configuration..."
php artisan config:cache 2>&1

echo "Caching routes..."
php artisan route:cache 2>&1

echo "Caching views..."
php artisan view:cache 2>&1

echo "Ensuring storage symlink..."
php artisan storage:link || true

echo "Starting PHP-FPM..."
php-fpm -D

sleep 2

echo "Checking PHP-FPM status..."
ps aux | grep -i "php-fpm" | grep -v grep || echo "PHP-FPM not running!"

echo "Starting Nginx..."
exec nginx -g 'daemon off;'

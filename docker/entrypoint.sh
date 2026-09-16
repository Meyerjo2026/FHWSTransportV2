#!/bin/sh
set -e

echo "Waiting for database..."
tries=0
until php artisan db:show > /dev/null 2>&1 || [ "$tries" -ge 30 ]; do
    tries=$((tries + 1))
    sleep 2
done

if [ "$tries" -ge 30 ]; then
    echo "Database did not become reachable in time; continuing anyway (migrate will surface the real error)."
fi

echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Ensuring storage symlink..."
php artisan storage:link || true

exec "$@"

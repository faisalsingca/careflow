#!/usr/bin/env bash
set -e

if [ -n "$DATABASE_URL" ] && [ -z "$DB_URL" ]; then
    export DB_URL="$DATABASE_URL"
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

for attempt in 1 2 3 4 5; do
    if php artisan migrate --force; then
        break
    fi

    if [ "$attempt" = "5" ]; then
        exit 1
    fi

    sleep 5
done

php artisan config:cache

exec apache2-foreground

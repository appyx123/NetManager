#!/bin/sh
set -e

# Ensure all required storage subdirectories exist (crucial when mounted as fresh volume)
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/app/public \
         /var/www/html/storage/app/private \
         /var/www/html/storage/logs

# Set directory permissions for web user
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure storage symlink exists
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link || true
fi

# Generate APP_KEY if not already set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Wait for database connection if DB_HOST is configured
if [ -n "$DB_HOST" ] && [ "$DB_CONNECTION" = "mysql" ]; then
    echo "Waiting for database connection at $DB_HOST:${DB_PORT:-3306}..."
    MAX_TRIES=30
    COUNT=0
    until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Throwable \$e) { exit(1); }" 2>/dev/null; do
        COUNT=$((COUNT + 1))
        if [ $COUNT -ge $MAX_TRIES ]; then
            echo "Warning: Database connection timeout. Proceeding anyway..."
            break
        fi
        sleep 2
    done
    echo "Database reachable!"
fi

# Optional automated migration flag
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

# Clear or cache configuration
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    php artisan optimize:clear || true
fi

exec "$@"

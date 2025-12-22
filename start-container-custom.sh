
#!/usr/bin/env bash
set -e

composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY no está configurada en Railway." >&2
  exit 1
fi

php artisan migrate --force
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

export PHP_CLI_SERVER_WORKERS=1
exec php artisan serve --host 0.0.0.0 --port "${PORT}"

#!/bin/bash
#Запускается внутри контейнера
set -e

MAX_RETRIES=30
SLEEP_TIME=2

echo "⌛ Ждём Laravel (PHP-FPM)..."
RETRY=0
until php artisan --version >/dev/null 2>&1 || [ $RETRY -ge $MAX_RETRIES ]; do
  echo "   Laravel ещё не готов... ($RETRY/$MAX_RETRIES)"
  RETRY=$((RETRY+1))
  sleep $SLEEP_TIME
done

echo "📦 Выполняем оптимизацию Composer и Laravel..."

# Выполняем post-install скрипты Composer (package:discover и т.д.)
composer dump-autoload --optimize --no-interaction || true
php artisan package:discover --ansi || true

echo "📦 Выполняем миграции и очистку кешей..."

php artisan migrate --force --seed
php artisan optimize:clear
php artisan scribe:generate --verbose


echo "✅ Миграции и кеши успешно применены!"

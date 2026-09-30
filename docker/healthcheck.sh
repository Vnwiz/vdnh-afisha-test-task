#!/bin/sh

# Проверяем что nginx отвечает
if ! curl -f http://localhost/health > /dev/null 2>&1; then
    echo "Nginx health check failed"
    exit 1
fi

# Проверяем что PHP-FPM работает
if ! php-fpm-healthcheck > /dev/null 2>&1; then
    echo "PHP-FPM health check failed"
    exit 1
fi

exit 0
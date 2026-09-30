# --- STAGE 1: PHP-FPM образ ---
FROM php:8.3-fpm as php_fpm_image
# Аргументы для динамического UID/GID
ARG USER_ID=1000
ARG GROUP_ID=1000
ARG USER_NAME=www-user

WORKDIR /var/www

# Меняем стандартный репозиторий Debian на CDN-зеркало для большей надежности
RUN sed -i 's/deb.debian.org/cdn.debian.net/g' /etc/apt/sources.list.d/debian.sources

# Копируем composer файлы для установки зависимостей (для кэширования слоев)
COPY composer.json composer.lock* ./

# Устанавливаем системные зависимости и расширения
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    autoconf \
    libmemcached-dev \
    libevent-dev \
    zlib1g-dev \
    libsasl2-dev \
    libpq-dev \
    libzip-dev \
    curl \
    git \
    supervisor \
    unzip \
    libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP расширения
RUN docker-php-ext-install pdo pdo_pgsql zip pcntl bcmath sockets soap

# Redis и Memcached
RUN pecl install redis memcached && \
    docker-php-ext-enable redis memcached

# Создаем пользователя и группу с динамическими ID (без ошибок, если существуют)
RUN getent group ${GROUP_ID} || groupadd -g ${GROUP_ID} ${USER_NAME} && \
    id -u ${USER_ID} >/dev/null 2>&1 || useradd -u ${USER_ID} -ms /bin/bash -g ${USER_NAME} ${USER_NAME}

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chown www-data:www-data /usr/local/bin/composer

# Устанавливаем зависимости Composer без выполнения скриптов (artisan еще не скопирован)
# Для dev окружения устанавливаем с dev-пакетами (включая Scribe)
RUN if [ -f composer.json ]; then \
        composer install --optimize-autoloader --no-interaction --prefer-dist --no-scripts; \
    fi

# Копируем остальные файлы приложения (исключая vendor, который уже установлен)
COPY . .

# Supervisor конфиги
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/supervisord-scheduler.conf /etc/supervisor/supervisord-scheduler.conf
COPY docker/supervisor/scheduler.conf /etc/supervisor/conf.d/scheduler.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

RUN mkdir -p /var/log && \
    chmod 777 /var/log

RUN mkdir -p /var/www/storage/app/public && \
    mkdir -p /var/www/storage/logs/supervisor && \
    chown -R ${USER_NAME}:${USER_NAME} /var/www/storage/app/public && \
    chown -R ${USER_NAME}:${USER_NAME} /var/www/storage/logs && \
    chmod -R 775 /var/www/storage/app/public && \
    chmod -R 775 /var/www/storage/logs

# Настраиваем права (включая vendor)
RUN chown -R ${USER_NAME}:${USER_NAME} /var/www && \
    chmod +x /var/www/init.sh && \
    chmod -R 755 /var/www/vendor 2>/dev/null || true

USER ${USER_NAME}

EXPOSE 9000
CMD ["php-fpm"]

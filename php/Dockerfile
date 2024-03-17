# Используем образ с PHP 8.2-FPM
FROM php:8.2-fpm

# Устанавливаем необходимые пакеты
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    zip \
    unzip \
    git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql

# Используем официальный PHP-образ на базе Alpine
FROM php:8.2-fpm-alpine

# Устанавливаем необходимые пакеты и расширения PHP
RUN apk update && apk add --no-cache \
    mysql-client \
    zip \
    unzip \
    git \
    libpq \
    libzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html/app

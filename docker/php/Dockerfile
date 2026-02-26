FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

# System deps
RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    nodejs \
    npm \
    postgresql-dev \
    $PHPIZE_DEPS

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    intl \
    mbstring \
    zip \
    gd \
    exif

# ✅ Redis (phpredis)
RUN pecl install redis \
 && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 www \
 && adduser -G www -g www -s /bin/sh -D -u 1000 www

USER www
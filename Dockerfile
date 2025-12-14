# PHP-FPM image for Laravel
FROM php:8.4-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libpng libpng-dev \
    libzip libzip-dev \
    oniguruma-dev \
    icu icu-dev \
    freetype freetype-dev \
    libjpeg-turbo libjpeg-turbo-dev \
    zlib zlib-dev \
    bash \
    mysql-client \
    openssl \
    autoconf g++ make \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring zip gd intl bcmath \
 && apk del autoconf g++ make

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy composer files first (for caching) then install
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist || true

# Copy the rest of the application
COPY . .

# Ensure storage and bootstrap cache exist and are writable
RUN mkdir -p storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

CMD ["php-fpm"]

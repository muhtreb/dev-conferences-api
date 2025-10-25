# Base image
FROM php:8.4-fpm-alpine

# Install necessary packages for both PHP and Nginx
RUN apk update \
    && apk add --no-cache \
        nginx \
        vim \
        unzip \
        iputils \
        icu-dev \
        postgresql-dev \
        zlib-dev \
        libzip-dev \
        supervisor \
        git \
        curl \
    && rm -rf /var/cache/apk/*

# Configure PHP
COPY ./docker/prod/php-fpm/docker-www.conf /etc/php/8.4/fpm/conf.d/docker-www.conf
COPY ./docker/prod/php-fpm/php-ini-overrides.ini /etc/php/8.4/fpm/conf.d/99-overrides.ini

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pdo_mysql

# APP_ENV is set to prod
ENV APP_ENV=prod

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Remove vendor directory if it exists
RUN rm -rf /application/vendor

# Install the composer dependencies
COPY . /application
WORKDIR /application
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Set up Nginx
COPY ./docker/prod/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Configure Supervisor
COPY ./docker/prod/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Expose ports for Nginx and PHP-FPM
EXPOSE 8080

# Start Supervisord to manage both services
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
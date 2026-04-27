# Base image
FROM php:8.5-fpm-alpine

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
COPY ./docker/prod/php-fpm/docker-www.conf /usr/local/etc/php-fpm.d/zz-docker-www.conf
COPY ./docker/prod/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pdo_mysql

# APP_ENV is set to prod
ENV APP_ENV=prod

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application sources
COPY . /application
WORKDIR /application

# Reset vendor and var directories, then create the writable Symfony dirs
RUN rm -rf vendor var \
    && mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

# Install the composer dependencies
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Set up Nginx
COPY ./docker/prod/nginx/nginx-main.conf /etc/nginx/nginx.conf
COPY ./docker/prod/nginx/site.conf /etc/nginx/conf.d/default.conf

# Configure Supervisor
COPY ./docker/prod/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Entrypoint clears the cache and runs migrations on every container start
COPY ./docker/prod/webserver/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose ports for Nginx and PHP-FPM
EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start Supervisord to manage both services
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

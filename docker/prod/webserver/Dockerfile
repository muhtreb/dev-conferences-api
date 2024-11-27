# Base image
FROM php:8.4-fpm

# Install necessary packages for both PHP and Nginx
RUN apt-get clean && apt-get update \
    && apt-get -y --no-install-recommends install \
        nginx \
        locales \
        vim \
        unzip \
        iputils-ping \
        libicu-dev \
        libpq-dev \
        zlib1g-dev \
        libzip-dev \
        supervisor \
        git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Set up locales
RUN locale-gen en_US.UTF-8 && locale-gen fr_FR.UTF-8

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
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

# Set up Nginx
COPY ./docker/prod/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Configure Supervisor
COPY ./docker/prod/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Expose ports for Nginx and PHP-FPM
EXPOSE 8080

# Start Supervisord to manage both services
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

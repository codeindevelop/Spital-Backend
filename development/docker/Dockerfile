FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk update && apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    $PHPIZE_DEPS \
    librdkafka-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    xml \
    zip \
    bcmath \
    intl \
    gd

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install rdkafka extension
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy custom php.ini
COPY ./php.ini /usr/local/etc/php/conf.d/custom.ini

# Set working directory
WORKDIR /var/www

# Add user for laravel application
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Copy app source code
COPY --chown=www:www ./../../ /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

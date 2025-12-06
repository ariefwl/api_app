FROM dunglas/frankenphp:php8.3-alpine

ENV SERVER_NAME=":80"

RUN apk update \
    && apk add --no-cache \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    bash 

RUN install-php-extensions \
    gd \
    pcntl \
    opcache \
    pdo \
    sqlsrv \
    pdo_sqlsrv \
    pdo_mysql \
    zip \
    bcmath \
    intl \
    exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# WORKDIR /var/www/html
WORKDIR /app

# Copy the Laravel application files into the container.
# COPY . .
COPY --chown=www-data:www-data . /app

COPY . .

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

COPY ./.docker/php/php.ini /usr/local/etc/php/

# install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

RUN chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT [ "php", "artisan", "octane:frankenphp" ]

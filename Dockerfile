# Use the official PHP 8.1 image with FPM
FROM php:8.1-fpm

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

# Install PDO and PDO MySQL extensions

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"
# Copy the application code into the container
COPY ./app /var/www/html

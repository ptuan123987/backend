# FROM elrincondeisma/octane:latest
# RUN curl -sS https://getcomposer.org/installer​ | php -- \
#      --install-dir=/usr/local/bin --filename=composer

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

# WORKDIR /app
# COPY . .
# RUN rm -rf /app/vendor
# RUN rm -rf /app/composer.lock
# RUN composer install
# RUN composer require laravel/octane spiral/roadrunner
# COPY .env.example .env
# RUN mkdir -p /app/storage/logs
# RUN php artisan cache:clear
# RUN php artisan view:clear
# RUN php artisan config:clear
# RUN php artisan octane:install --server="swoole"
# CMD php artisan octane:start --server="swoole" --host="0.0.0.0"

# EXPOSE 8000


FROM php:8.2-fpm-alpine

# Update app
RUN apk update && apk add --no-cache tzdata
# Set timezone
ENV TZ="Asia/Ho_Chi_Minh"

RUN apk add --update --no-cache autoconf g++ make openssl-dev
RUN apk add libpng-dev
RUN apk add libzip-dev
RUN apk add --update linux-headers

RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install sockets
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
### End Init install

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

WORKDIR /home/source/main

COPY . .
COPY composer.json .
COPY composer.lock .
COPY .env.example .env
# Create a dummy artisan file
RUN touch artisan

RUN composer update

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache && \
    mkdir -p bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap

RUN chmod -R 777 storage bootstrap
RUN chmod -R 777 storage
RUN php artisan cache:clear
RUN php artisan config:cache



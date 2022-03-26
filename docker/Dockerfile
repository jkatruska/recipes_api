FROM php:8.1.4-fpm-alpine3.15

WORKDIR /app

RUN docker-php-ext-install pdo_mysql

COPY ./zz-symfony.ini $PHP_INI_DIR/conf.d/zz-symfony.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
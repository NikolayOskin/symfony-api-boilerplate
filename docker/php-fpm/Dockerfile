FROM php:7.4-fpm-alpine

RUN apk update && apk add unzip && docker-php-ext-install pdo pdo_mysql

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /var/www
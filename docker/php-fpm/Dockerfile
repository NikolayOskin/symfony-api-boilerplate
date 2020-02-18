FROM php:7.4-fpm-alpine

RUN set -ex \
    && apk update && apk add unzip \
	&& apk --no-cache add postgresql-libs postgresql-dev \
	&& docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
	&& docker-php-ext-install pgsql pdo_pgsql \
	&& apk del postgresql-dev

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f redis  \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && rm -rf /usr/share/php \
    && rm -rf /tmp/* \
    && apk del  .phpize-deps

# symfony console alias
RUN apk add --no-cache --upgrade bash
RUN echo 'alias c="php bin/console"' >> ~/.bashrc

WORKDIR /var/www
FROM php:7.2.14-fpm-alpine as fpm_base

RUN apk add --no-cache --virtual .deps \
                git \
                icu-libs \
                zlib \
                openssh \
                imagemagick \
                imagemagick-libs \
                imagemagick-dev

ENV APCU_VERSION 5.1.8

RUN set -xe \
        && apk add --no-cache --virtual .build-deps \
                $PHPIZE_DEPS \
                icu-dev \
                zlib-dev \
        && docker-php-ext-install \
                intl \
                pdo_mysql \
                zip \
        && pecl install \
                xdebug-2.6.0\
                apcu-${APCU_VERSION} \
                apcu_bc \
                imagick \
                redis \
        && docker-php-ext-enable --ini-name 20-apcu.ini apcu \
        && docker-php-ext-enable --ini-name 21-apc.ini apc \
        && docker-php-ext-enable --ini-name 05-opcache.ini opcache \
        && docker-php-ext-enable --ini-name 20-imagick.ini imagick \
        && docker-php-ext-enable xdebug \
        && docker-php-ext-enable --ini-name 20-redis.ini redis

#  && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \

COPY php.ini /usr/local/etc/php/php.ini

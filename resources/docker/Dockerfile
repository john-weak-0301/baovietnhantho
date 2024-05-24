FROM php:7.4-fpm-bullseye

# General settings
WORKDIR /var/www/html

ENV LC_ALL=en_US.UTF-8
RUN apt update && \
    apt -y install libicu-dev libonig-dev locales && \
    localedef -f UTF-8 -i en_US en_US.UTF-8 && \
    docker-php-ext-install intl

# Composer
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer

# GD library
RUN apt -y install libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

# cleaning
RUN apt clean && \
    rm -rf /var/lib/apt/lists/*

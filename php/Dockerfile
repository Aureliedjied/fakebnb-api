FROM php:8.3-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev libpq-dev iputils-ping default-mysql-client \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && curl -sSk https://getcomposer.org/installer | php -- --disable-tls \
    && mv composer.phar /usr/local/bin/composer \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/



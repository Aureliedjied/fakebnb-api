FROM php:8.3-apache

# Regrouper les RUN pour réduire le nombre de layers
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    # Installation des dépendances en une seule commande
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        locales \
        apt-utils \
        git \
        libicu-dev \
        g++ \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        libxslt-dev \
        libpq-dev \
        iputils-ping \
        default-mysql-client \
    # Configuration des locales
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    # Installation de Composer de manière sécurisée
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    # Configuration et installation des extensions PHP
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        opcache \
        intl \
        zip \
        calendar \
        dom \
        mbstring \
        xsl \
    # Installation d'APCu
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    # Activer le module mod_rewrite
    && a2enmod rewrite \
    # Nettoyage
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/*

WORKDIR /var/www/



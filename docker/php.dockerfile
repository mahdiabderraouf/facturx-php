FROM php:8.0-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    poppler-utils \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["apache2-foreground"]

FROM php:8.1-apache

WORKDIR /var/www/html

RUN useradd -u 1000 -m hostUser && \
    usermod -aG hostUser www-data

RUN apt-get update && apt-get install -y \
    poppler-utils \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["apache2-foreground"]

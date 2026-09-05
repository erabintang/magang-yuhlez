FROM php:8.2-apache

RUN apt-get update && apt-get install -y libzip-dev zip unzip \
	&& docker-php-ext-install pdo_mysql zip \
	&& rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
	&& sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader \
	&& cp .env.example .env \
	&& php artisan key:generate \
	&& chown -R www-data:www-data storage bootstrap/cache
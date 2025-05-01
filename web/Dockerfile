FROM php:8.4-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN a2enmod rewrite
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
WORKDIR /var/www/html
COPY ./app /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN [ -f composer.json ] && composer install --no-dev --optimize-autoloader || true
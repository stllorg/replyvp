# Base from OS Debian Bookworm with php and apache
FROM php:8.4-apache

# Install mysqli driver to work with MySQL database
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Configure apache
RUN a2enmod rewrite
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Get Composer (PHP Depency Manager)
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Copy app project files to folder /var/www/html and set folder permisisons
WORKDIR /var/www/html
COPY ./app /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Install required git and zip for Composer
RUN apt-get update && apt-get install -y --no-install-recommends zip unzip git
# Clear composer cache
RUN composer clear-cache

ARG APP_ENV
ENV APP_ENV=${APP_ENV}

# Install required dependencies
RUN if [ "$APP_ENV" = "development" ]; \
      then composer install; \
      else \
      composer install --no-dev --optimize-autoloader; \
      fi
RUN echo "The project environment is ${APP_ENV}"
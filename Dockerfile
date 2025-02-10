FROM php:8.3-apache
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions gd xdebug pdo pdo_pgsql

RUN a2enmod rewrite

COPY . /var/www/html/

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

RUN composer update

RUN chown -R www-data:www-data /var/www/html
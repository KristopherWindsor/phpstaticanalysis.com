FROM php:7.2-apache

RUN apt-get update && apt-get install -y zip unzip

RUN a2enmod rewrite

COPY ./code /code/
COPY ./html /var/www/html/
RUN mkdir -p /code/demo/workingdir && chmod 0777 /code/demo/workingdir

RUN chown -R www-data:www-data /var/www/html/

# Install composer for /code/demo
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd /code/demo && composer install

FROM php:7.2-apache
RUN apt-get update && apt-get install -y
RUN a2enmod rewrite
COPY ./code /code/
COPY ./html /var/www/html/
RUN chown -R www-data:www-data /var/www/html/

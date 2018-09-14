FROM php:7.2-apache
RUN apt-get update && apt-get install -y
COPY ./src /var/www/html/
RUN chown -R www-data:www-data /var/www/html/

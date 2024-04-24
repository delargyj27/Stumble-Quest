FROM php:8.2-apache

COPY ./src/ /var/www/html/

# TODO: Copy php.ini file(s)

# TODO: Copy apache config file(s)

# Enable Apache modules
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql

EXPOSE 80
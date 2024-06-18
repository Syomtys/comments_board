FROM php:7.4-apache

COPY custom_configuration.conf /etc/apache2/sites-available/custom_configuration.conf
RUN a2ensite custom_configuration.conf
RUN service apache2 restart
RUN a2enmod rewrite
RUN apt-get update
RUN apt-get install -y \
    nano \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
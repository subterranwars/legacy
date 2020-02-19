FROM php:5.6-apache

# Install php mysql support
RUN docker-php-ext-install mysqli

# Install php gd support
RUN apt-get update -y && apt-get install -y libpng-dev
RUN apt-get update -y && apt-get install -y libjpeg62-turbo-dev
RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/lib
RUN docker-php-ext-install gd
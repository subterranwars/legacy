FROM php:5.6-apache

# Install php mysql support
RUN docker-php-ext-install mysqli

# Install php gd support
RUN apt-get update -y && apt-get install -y libpng-dev
RUN apt-get update -y && apt-get install -y libjpeg62-turbo-dev
RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/lib
RUN docker-php-ext-install gd

# Install mail support
RUN apt-get update -y && apt-get install -y ssmtp mailutils

# root is the person who gets all mail for userids < 1000
RUN echo "root=support@subterranwars.de" >> /etc/ssmtp/ssmtp.conf
RUN echo "mailhub=mail" >> /etc/ssmtp/ssmtp.conf
RUN echo "AuthUser=user@subterranwars.de" >> /etc/ssmtp/ssmtp.conf
RUN echo "AuthPass=pwd" >> /etc/ssmtp/ssmtp.conf

# Set up php sendmail config
RUN echo "sendmail_path=sendmail -i -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini

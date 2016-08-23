FROM php:5.6-apache
MAINTAINER Pratomo Ardianto <ardi@x-team.com>

# Install packages
RUN apt-get update
RUN apt-get install -y \
        git \
        vim \
    && pecl install intl \
    && echo extension=intl.so >> /usr/local/etc/php/conf.d/ext-intl.ini \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get clean

RUN docker-php-ext-install mysqli opcache

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
FROM php:8.3-apache

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions @composer opcache && \
    ln -s composer /usr/local/bin/c #shortcut for composer

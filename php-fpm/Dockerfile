# Użycie obrazu PHP 8.2 FPM
FROM php:8.2-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Czyszczenie cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Instalacja Composera
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalacja Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Set xdebug
RUN touch /var/www/xdebug.log
RUN chmod 777 /var/www/xdebug.log

RUN echo -e "\n\
xdebug.client_host = host.docker.internal \n\
xdebug.idekey = PHPStorm \n\
xdebug.mode=debug \n\
xdebug.start_with_request=yes \n\
xdebug.remote_handler = "dbgp" \n\
xdebug.client_port = 9000 \n\
xdebug.log = /var/www/xdebug.log \n\
" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
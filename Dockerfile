FROM php:8.1.9-fpm-alpine3.16

# Arguments defined in docker-compose.yml
RUN rm -f /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.16/main" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.16/community" >> /etc/apk/repositories


RUN apk add --update --no-cache ${PHPIZE_DEPS} \
        nginx \
        openssl-dev\
        supervisor\
        dcron \
        zlib \
        zlib-dev \
        libzip\
        libzip-dev \
        libpng \
        libpng-dev \
        gmp-dev


RUN docker-php-ext-install -j$(nproc) \
		bcmath \
		gd \
		mysqli \
		pdo \
		pdo_mysql \
        gmp \
        zip \
        pcntl


RUN pecl install redis mongodb swoole && \
    docker-php-ext-enable redis mongodb swoole && \
    docker-php-ext-configure opcache --enable-opcache && \
    echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf composer-setup.php

# Installing
RUN mkdir -p /var/www/html

# Setup Working Dir
WORKDIR /var/www/html
COPY . .
COPY docker/index.php .
COPY env /var/www/html/.env
RUN mkdir -p /var/www/html/storage/logs && \
    touch /var/www/html/storage/logs/laravel.log

RUN chown -R www-data:www-data /var/www/html

# Install project requirements and finish build steps
USER www-data
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-plugins --no-scripts --no-cache

# Prepare nginx and php-fpm configuration
USER root
#PHP : TODO: Check if it works or not
COPY .docker/php/hermes.ini $PHP_INI_DIR/conf.d/hermes.ini
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY .docker/entrypoint.sh /usr/bin/entrypoint.sh
RUN chmod +x /usr/bin/entrypoint.sh

#Supervisord
COPY ./docker/supervisor/hermes.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir /var/log/supervisor && \
    touch /var/log/supervisor/supervisord.log && \
    chmod -R 777 /var/log/supervisor /var/run

# Setup Crontab
RUN touch crontab.tmp && \
    echo '* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1' >> crontab.tmp && \
    crontab crontab.tmp && \
    rm -rf crontab.tmp

# Expose ports
EXPOSE 80 6002
ENTRYPOINT ["/usr/bin/entrypoint.sh"]

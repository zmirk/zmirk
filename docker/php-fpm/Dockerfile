FROM php:8.3-fpm-alpine

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apk update && apk add autoconf build-base linux-headers libpq-dev

RUN pecl install redis-5.3.7 \
	&& pecl install xdebug-3.3.1 \
	&& docker-php-ext-enable redis xdebug

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql

# Install supervisor
RUN apk --update add supervisor

RUN mkdir -p /var/www/storage/logs &&\
    touch /var/www/storage/logs/worker.log

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN chown -R www-data:www-data /var/www

WORKDIR /var/www

EXPOSE 9000

ENTRYPOINT ["php-fpm"]

# docker exec php-fpm /usr/bin/supervisord -c /etc/supervisor/conf.d/laravel-worker.conf
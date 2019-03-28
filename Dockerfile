FROM php:7.2-alpine

RUN apk add --no-cache g++ make autoconf icu-dev

# Install PHP internationalization extension and xdebug.
RUN pecl install xdebug \
    && docker-php-ext-install intl \
    && { \
        echo 'xdebug.remote_enable=1'; \
        echo 'xdebug.remote_autostart=1'; \
        echo 'xdebug.max_nesting_level=1200'; \
        echo 'xdebug.idekey = PHPSTORM'; \
    } > /usr/local/etc/php/conf.d/99-docker-php-ext-xdebug.ini \
    && docker-php-ext-enable xdebug --ini-name 99-docker-php-ext-xdebug.ini

RUN apk del autoconf g++ make

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

ARG builduser

USER $builduser

WORKDIR /library
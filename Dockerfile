FROM php:7.2-alpine

RUN apk --no-cache add icu-dev

# Install PHP internationalization extension.
RUN docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

ARG builduser

USER $builduser

WORKDIR /library
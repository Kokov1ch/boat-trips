FROM php:8.4-cli-alpine3.23 AS base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl zip opcache sockets

COPY --from=spiralscout/roadrunner:2025.1.5 /usr/bin/rr /usr/local/bin/rr
COPY --from=composer:2.8 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

FROM base AS dev

RUN install-php-extensions xdebug

ENV DOCKER_APP_UID=1000
RUN apk add --no-cache shadow && usermod -u $DOCKER_APP_UID www-data
USER www-data

CMD ["rr", "serve", "-c", ".rr.dev.yml"]

FROM base AS prod

ENV APP_ENV=prod

COPY docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/
RUN rm -f /usr/local/etc/php/conf.d/xdebug.ini

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    chown -R www-data:www-data /var/www
USER www-data

EXPOSE 8080

CMD ["rr", "serve", "-c", ".rr.yml"]
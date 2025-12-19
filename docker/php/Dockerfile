FROM php:8.4-cli-alpine3.23 AS base

ENV DOCKER_APP_UID=1000

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl zip opcache sockets

COPY --from=spiralscout/roadrunner:2025.1.5 /usr/bin/rr /usr/local/bin/rr
COPY --from=composer:2.9.2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

FROM base AS dev

RUN install-php-extensions xdebug && \
    apk add --no-cache shadow && \
    usermod -u $DOCKER_APP_UID www-data && \
    apk del shadow
USER www-data

CMD ["rr", "serve", "-c", ".rr.dev.yml"]

FROM base AS prod

ENV APP_ENV=prod

COPY docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/
RUN rm -f /usr/local/etc/php/conf.d/xdebug.ini

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY --chown=www-data:www-data . .

RUN php bin/console asset-map:compile && \
    php bin/console cache:clear && \
    php bin/console cache:warmup

USER www-data
EXPOSE 8080
CMD ["rr", "serve", "-c", ".rr.yml"]

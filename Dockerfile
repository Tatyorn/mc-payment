FROM dunglas/frankenphp:1-php8.4 AS base

RUN install-php-extensions \
    bcmath \
    exif \
    gd \
    intl \
    pdo_mysql \
    pcntl \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /app

RUN mkdir -p \
    bootstrap/cache \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs && \
    chown -R www-data:www-data bootstrap/cache storage

FROM node:23 AS npm
WORKDIR /app
RUN mkdir -p /app/public/build
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts
COPY vite.config.js ./
COPY resources/ resources/
RUN npm run build

FROM base AS vendor
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --prefer-dist \
    --audit \
    --no-scripts

FROM base AS final

COPY . .
COPY --from=npm /app/public/build public/build
COPY --from=vendor /app/vendor vendor
COPY Caddyfile /etc/caddy/Caddyfile

RUN rm -rf bootstrap/cache/*.php public/storage && \
    php artisan package:discover --ansi && \
    php artisan storage:link --force && \
    cp vendor/laravel/octane/bin/frankenphp-worker.php frankenphp-worker.php && \
    cp vendor/laravel/octane/bin/bootstrap.php bootstrap.php

RUN chown -R www-data:www-data \
    bootstrap/cache \
    storage \
    public

ENV APP_BASE_PATH=/app
EXPOSE 8080

HEALTHCHECK --interval=10s --timeout=3s --retries=3 \
    CMD curl -f http://localhost:8080/up || exit 1

ENTRYPOINT ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

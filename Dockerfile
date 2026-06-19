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

RUN rm -rf public/storage && php artisan storage:link --force

RUN chown -R www-data:www-data \
    bootstrap/cache \
    storage \
    public

ENV PORT=8080
EXPOSE 8080

HEALTHCHECK --interval=10s --timeout=3s --retries=3 \
    CMD php -r "echo @file_get_contents('http://localhost:8080/up') ? 'ok' : 'fail';" 2>/dev/null || exit 1

ENTRYPOINT ["frankenphp", "php-server", "-r", "public/index.php"]

# --- Stage 1: build front-end assets (Breeze/Vite scaffold; the app's own
# pages use static CSS, but this keeps any @vite() usage from breaking) ---
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# --- Stage 2: PHP application image ---
FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libpq-dev libicu-dev libonig-dev \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql pdo_mysql zip intl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Apache should serve Laravel's public/ directory, not the app root.
COPY docker/apache-app.conf /etc/apache2/sites-available/000-default.conf

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]

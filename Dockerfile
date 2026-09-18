# --- Stage 1: build front-end assets ---
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY postcss.config.js tailwind.config.js vite.config.js ./
RUN npm run build && npm prune --production

# --- Stage 2: PHP application runtime ---
FROM php:8.3-fpm-alpine

# Install build dependencies, install extensions, then clean up build tools
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libzip-dev libpq-dev icu-dev oniguruma-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql pdo_mysql zip intl \
    && apk del .build-deps

# Install production runtime dependencies only
RUN apk add --no-cache libzip libpq icu-libs oniguruma nginx tini

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create storage and cache directories
RUN mkdir -p storage/framework/cache \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             storage/app/public \
             bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Install PHP dependencies (separate layer for cache efficiency)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --optimize-autoloader

# Copy application code
COPY . .

# Copy built assets from Node stage
COPY --from=assets /app/public/build ./public/build

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev --apcu

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-docker.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/zzz-docker.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD wget -qO- http://localhost:8080/up || exit 1

ENTRYPOINT ["/sbin/tini", "--"]
CMD ["/usr/local/bin/entrypoint.sh"]

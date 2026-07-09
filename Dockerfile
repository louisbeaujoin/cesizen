FROM php:8.3-apache

# ── Outils système + Node.js 20 ────────────────────────────
RUN apt-get update && apt-get install -y \
        git zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
        ca-certificates gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# ── Composer depuis son image officielle ───────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ── Code source (vendor et node_modules exclus via .dockerignore)
COPY . .

# ── Dépendances PHP (production), assets frontend ──────────
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

# ── Permissions Laravel ────────────────────────────────────
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ── Config Apache (DocumentRoot sur /public) ───────────────
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# ── Script de démarrage ────────────────────────────────────
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]

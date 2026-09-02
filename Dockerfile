# ---- Build frontend CSS ----
FROM node:20-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY src ./src
COPY site ./site
COPY assets ./assets

RUN npm run build

# ---- PHP / Kirby runtime ----
FROM php:8.3-apache-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip intl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=assets /app/assets/css/output.css ./assets/css/output.css

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p media site/cache site/sessions site/accounts \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwX content media site/cache site/sessions site/accounts

# Allow Kirby .htaccess rewrites
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY docker/start.sh /usr/local/bin/start-kirby.sh
RUN chmod +x /usr/local/bin/start-kirby.sh

EXPOSE 80

CMD ["start-kirby.sh"]

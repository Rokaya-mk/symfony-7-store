FROM php:8.5-fpm
RUN apt-get update && apt-get install -y git unzip libzip-dev libicu-dev \
 && docker-php-ext-install -j1 pdo_mysql zip intl \
 && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
COPY .env.example .env
RUN composer install --no-interaction --optimize-autoloader
RUN APP_ENV=prod php bin/console importmap:install \
 && APP_ENV=prod php bin/console asset-map:compile
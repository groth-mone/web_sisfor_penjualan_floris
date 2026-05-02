FROM php:8.2-cli

WORKDIR /app

COPY . .

# PERBAIKAN: Instal libmariadb-dev dan pdo_mysql untuk MySQL
RUN apt-get update && apt-get install -y \
    unzip git curl libmariadb-dev \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

CMD sh -c "php artisan serve --host=0.0.0.0 --port=\${PORT:-10000}"
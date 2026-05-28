FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN php artisan key:generate --no-interaction 2>/dev/null || true

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
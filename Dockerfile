# PHP 8.3 FPM image
FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libzip-dev \
    oniguruma-dev \
    git \
    unzip \
    sqlite-dev \
    linux-headers \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    pdo_mysql \
    pdo_sqlite \
    bcmath \
    gd \
    zip \
    mbstring \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install dependencies and build assets
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install && npm run build

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8000

ENTRYPOINT ["entrypoint.sh"]

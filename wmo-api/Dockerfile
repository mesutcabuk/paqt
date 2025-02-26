FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Set file permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Set user to www-data
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]

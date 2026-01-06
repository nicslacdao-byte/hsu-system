# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install tools for Postgres & Laravel
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install PHP drivers
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# 4. Enable URL rewriting for Laravel
RUN a2enmod rewrite

# 5. Configure Apache to point to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Prepare the folder
WORKDIR /var/www/html
COPY . .

# 8. INSTALL THE MISSING FILES
RUN composer install --no-dev --optimize-autoloader

# 9. Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. THE MAGIC TRICK: Auto-Migrate & Start Server
CMD bash -c "php artisan config:clear && php artisan migrate --force && apache2-foreground"

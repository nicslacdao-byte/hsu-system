# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install dependencies for Laravel & Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
 && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
 && a2enmod rewrite \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory and Copy Files
WORKDIR /var/www/html
COPY . .

# 5. Install PHP Packages
RUN composer install --no-dev --optimize-autoloader

# 6. FIX PERMISSIONS (Crucial Step!)
# We give Apache permission to read ALL your files, not just storage
RUN chown -R www-data:www-data /var/www/html

# 7. FORCE APACHE CONFIG (The Magic Part)
# We overwrite the default setting to point directly to /public
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# 8. Start Server & Migrate
CMD bash -c "php artisan config:clear && php artisan migrate --force && apache2-foreground"

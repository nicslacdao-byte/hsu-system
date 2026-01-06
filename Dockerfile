# 1. Use PHP 8.2 with Apache (Standard Server)
FROM php:8.2-apache

# 2. Install tools needed for the database (Postgres) and images
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install PHP extensions (The drivers for Postgres & Laravel)
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# 4. Enable Apache mod_rewrite (Essential for Laravel URLs)
RUN a2enmod rewrite

# 5. Configure Apache to look at the 'public' folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Install Composer (The Dependency Manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Set working directory
WORKDIR /var/www/html

# 8. Copy all your files into the container
COPY . .

# 9. FORCE install dependencies (This fixes your error!)
RUN composer install --no-dev --optimize-autoloader

# 10. Fix permissions so the server can save files
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

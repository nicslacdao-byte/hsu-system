# Use a pre-made image that has PHP, Nginx, and Composer installed
FROM richarvey/nginx-php-fpm:latest

# Copy your project files into the server
COPY . /var/www/html

# Tell the server to install Laravel dependencies
ENV SKIP_COMPOSER 0
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Point the web server to the public folder
ENV WEBROOT /var/www/html/public

# Default Laravel settings
ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

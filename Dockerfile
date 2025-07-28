# Use an official PHP Apache image
FROM php:8.2-apache

# Install required PHP extensions (like MySQLi)
RUN docker-php-ext-install mysqli

# Copy app code to Apache's web directory
COPY . /var/www/html/

# Enable Apache rewrite module (optional, for pretty URLs)
RUN a2enmod rewrite

# Expose port 80 (default for Apache)
EXPOSE 80

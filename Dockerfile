FROM php:8.2-apache

WORKDIR /var/www/html

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable rewrite
RUN a2enmod rewrite

# Copy project
COPY . .

EXPOSE 80
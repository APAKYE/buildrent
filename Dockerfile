FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Apache virtual host config
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf

# Copy application files
COPY public/    /var/www/html/
COPY app/       /var/www/app/
COPY config/    /var/www/config/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]

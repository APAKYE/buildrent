FROM php:8.2-apache

RUN apt-get update && apt-get install -y default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf

COPY public/    /var/www/html/
COPY app/       /var/www/app/
COPY config/    /var/www/config/
COPY database/  /var/www/database/
COPY scripts/   /var/www/scripts/

RUN chmod +x /var/www/scripts/init_db.sh

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Use Railway's PORT environment variable
CMD bash -c "sed -i 's/80/'"${PORT:-80}"'/g' /etc/apache2/ports.conf && /var/www/scripts/init_db.sh && apache2-foreground"

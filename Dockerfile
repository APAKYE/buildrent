FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

COPY public/    /var/www/html/
COPY app/       /var/www/app/
COPY config/    /var/www/config/

RUN chown -R www-data:www-data /var/www/html

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]

#!/bin/bash
# Replace port 80 with Railway's PORT variable
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-enabled/000-default.conf
echo "Starting Apache on port $PORT"
exec apache2-foreground

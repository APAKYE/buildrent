#!/bin/bash
# Get Railway's port or default to 8080
PORT="${PORT:-8080}"
echo "=== BuildRent Starting ==="
echo "PORT variable is: $PORT"

# Disable mpm_event, enable mpm_prefork
a2dismod mpm_event 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Write ports config
echo "Listen $PORT" > /etc/apache2/ports.conf

# Write virtual host
cat > /etc/apache2/sites-enabled/000-default.conf << VHOST
<VirtualHost *:$PORT>
    DocumentRoot /var/www/html
    DirectoryIndex index.php
    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
VHOST

echo "=== Starting Apache on port $PORT ==="
exec apache2-foreground

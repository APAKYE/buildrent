#!/bin/bash
PORT=${PORT:-80}
echo "Configuring Apache for port $PORT"

# Fix MPM conflict - disable mpm_event, enable mpm_prefork
a2dismod mpm_event 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Rewrite ports config
cat > /etc/apache2/ports.conf << PORTS
Listen $PORT
PORTS

# Rewrite virtual host config
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

echo "Starting Apache on port $PORT"
exec apache2-foreground

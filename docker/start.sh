#!/bin/bash
PORT=${PORT:-80}
echo "Configuring Apache for port $PORT"
cat > /etc/apache2/ports.conf << PORTS
Listen $PORT
PORTS
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
exec apache2-foreground

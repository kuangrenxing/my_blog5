<VirtualHost *:80>
    DocumentRoot "/var/www/scst"
    ServerName scst.com

    <Directory "/var/www/scst">
        Options Indexes FollowSymLinks
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>

   # ErrorLog "/var/log/apache2/scst.com-error_log"
   # CustomLog "/var/log/apache2/scst.com-access_log" common
</VirtualHost>

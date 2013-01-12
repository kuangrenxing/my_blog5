<VirtualHost *:80>
    DocumentRoot "/var/www/mtcms"
    ServerName test.mtcms.com

    <Directory "/var/www/mtcms">
        Options Indexes FollowSymLinks
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>

   # ErrorLog "/var/log/apache2/test.mtcms.com-error_log"
   # CustomLog "/var/log/apache2/test.mtcms.com-access_log" common
</VirtualHost>

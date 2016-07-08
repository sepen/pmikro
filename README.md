# pmikro

## Installation
Just copy it to your desired destination (i.e: /var/www/vhosts/mysite.com)
  
## Requeriments
You will need Apache's mod_rewrite

* Example of Apache2 config file
```
<VirtualHost *:80>
  ServerName pmikro
  DocumentRoot "/var/www/vhosts/pmikro/pub"
  CustomLog ${APACHE_LOG_DIR}/pmikro.access.log Combined
  ErrorLog ${APACHE_LOG_DIR}/pmikro.error.log
  <Directory "/var/www/vhosts/pmikro/pub">
    AllowOverride All
    Order Deny,Allow
    Allow from All
  </Directory>
</VirtualHost>
```

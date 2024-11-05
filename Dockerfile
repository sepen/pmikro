FROM php:5.6.40-alpine

WORKDIR /var/www/pmikro

COPY app /var/www/pmikro/app/
COPY pub /var/www/pmikro/pub/
COPY vendor /var/www/pmikro/vendor/

EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/pmikro/pub"]

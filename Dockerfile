FROM php:5.6.40-alpine

WORKDIR /var/www/pmikro

COPY app /var/www/pmikro/app/
COPY pub /var/www/pmikro/pub/
COPY vendor /var/www/pmikro/vendor/

# Expose port 8000 for local development only (php -S is not production-ready)
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/pmikro/pub"]

# You can run the container with:
#   docker run -it --rm -p 8000:8000 -v $(pwd):/var/www/pmikro pmikro
# Then access the application at http://localhost:8000

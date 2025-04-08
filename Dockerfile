FROM php:8.4-alpine

WORKDIR /pmikro

COPY app /pmikro/app/
COPY pub /pmikro/pub/
COPY vendor /pmikro/vendor/

# Expose port 8000 for local development only (php -S is not production-ready)
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/pmikro/pub"]

# You can run the container with:
#   docker run -it --rm -p 8000:8000 -v $(pwd):/pmikro pmikro
# Then access the application at http://localhost:8000

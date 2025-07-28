FROM serversideup/php:8.3-fpm-nginx-alpine
WORKDIR /var/www/html
COPY . .

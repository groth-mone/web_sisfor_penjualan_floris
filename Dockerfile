FROM richarvey/nginx-php-fpm:php82

WORKDIR /var/www/html

COPY . .

# Set permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy nginx config untuk Laravel
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port
EXPOSE 8080

# Start nginx and php-fpm
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && nginx && php-fpm -F"]
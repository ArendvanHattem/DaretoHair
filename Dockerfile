FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Ensure scripts are in the right place
COPY scripts/* /scripts/
RUN chmod +x /scripts/*.sh

# Set environment variables
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false

# Run Composer directly during build
RUN composer install --no-dev --optimize-autoloader

# Run the deploy script for migrations
RUN /scripts/00-laravel-deploy.sh

CMD ["/start.sh"]
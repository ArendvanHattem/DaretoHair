FROM richarvey/nginx-php-fpm:php84

COPY . .
COPY scripts/* /scripts/
RUN chmod +x /scripts/*.sh

# Environment variables
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false

# Run Composer and deploy script during build
RUN composer install --no-dev --optimize-autoloader
RUN /scripts/00-laravel-deploy.sh

CMD ["/start.sh"]
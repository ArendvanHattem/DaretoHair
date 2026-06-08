FROM tangramor/nginx-php8-fpm:php8.4.5_node23.11.0

COPY . /var/www/html
COPY scripts/* /scripts/
COPY entrypoint.sh /entrypoint.sh

# Make scripts executable
RUN chmod +x /scripts/*.sh && chmod +x /entrypoint.sh

# Fix Composer permissions and run install
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# Run the deploy script (migrations, etc.)
RUN /scripts/00-laravel-deploy.sh

# Environment Variables
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV RUN_SCRIPTS 1
ENV APP_ENV production
ENV APP_DEBUG false

# Use our custom entrypoint to fix permissions at runtime
ENTRYPOINT ["/entrypoint.sh"]
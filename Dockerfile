FROM tangramor/nginx-php8-fpm:php8.4.5_node23.11.0

COPY . /var/www/html
COPY scripts/* /scripts/
RUN chmod +x /scripts/*.sh

# Environment variables
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV RUN_SCRIPTS 1
ENV APP_ENV production
ENV APP_DEBUG false

# Run Composer and deploy script during build
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader
RUN /scripts/00-laravel-deploy.sh

# Copy nginx config if needed (the image may have its own default)
COPY conf/nginx-site.conf /etc/nginx/conf.d/default.conf

CMD ["/start.sh"]
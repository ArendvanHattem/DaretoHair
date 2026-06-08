FROM php:8.4-fpm-alpine

# Install nginx, supervisor, bash, and required system packages
RUN apk add --no-cache nginx supervisor curl zip unzip git oniguruma-dev bash \
    && docker-php-ext-install pdo_mysql mbstring

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . /var/www/html
COPY scripts/* /scripts/
COPY entrypoint.sh /entrypoint.sh
COPY conf/nginx-site.conf /etc/nginx/http.d/default.conf

# Set up directories and permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache /tmp/php \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /tmp/php \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod 1777 /tmp/php \
    && chmod +x /scripts/*.sh /entrypoint.sh

# Set environment variables
ENV TMPDIR=/tmp/php

# Install Composer dependencies
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# Run Laravel setup script
RUN /scripts/00-laravel-deploy.sh

# Create Supervisor config with correct command paths
RUN echo '[supervisord]\nnodaemon=true\n' > /etc/supervisord.conf && \
    echo '[program:php-fpm]\ncommand=php-fpm -F\n' >> /etc/supervisord.conf && \
    echo '[program:nginx]\ncommand=nginx -g "daemon off;"\n' >> /etc/supervisord.conf && \
    cat /etc/supervisord.conf

EXPOSE 80

CMD ["/entrypoint.sh"]
FROM tangramor/nginx-php8-fpm:php8.4.5_node23.11.0

COPY . /var/www/html
COPY scripts/* /scripts/
COPY entrypoint.sh /entrypoint.sh
COPY conf/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY conf/php-fpm.conf /etc/php/8.4/fpm/conf.d/99-temp-dir.override.conf

RUN chmod +x /scripts/*.sh && chmod +x /entrypoint.sh

RUN cd /var/www/html && composer install --no-dev --optimize-autoloader
RUN /scripts/00-laravel-deploy.sh

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV RUN_SCRIPTS 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV TMPDIR=/tmp/php

# Override the base image's CMD with our entrypoint
ENTRYPOINT []
CMD ["/bin/bash", "/entrypoint.sh"]
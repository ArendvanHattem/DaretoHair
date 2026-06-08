FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Install required PHP extensions
RUN apk add --no-cache \
    php82-pdo_mysql \
    php82-pdo \
    php82-mysqli \
    php82-mbstring \
    php82-zip \
    php82-bcmath \
    php82-curl \
    php82-xml \
    php82-json \
    php82-tokenizer \
    php82-fileinfo

# Image config
ENV SKIP_COMPOSER 0
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false

CMD ["/start.sh"]
#FROM php:fpm-alpine
FROM composer:2.0 as composer
FROM php:7.4-fpm-alpine
COPY wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it
RUN apk --update --no-cache add git
RUN apk --update --no-cache add bash
#RUN pecl channel-update pecl.php.net

RUN set -eux \
    & apk add \
        --no-cache \
        nodejs \
        yarn

RUN apk add --no-cache $PHPIZE_DEPS
RUN apk add icu-dev
RUN apk add postgresql-dev
RUN apk add libxslt-dev
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql pgsql
RUN docker-php-ext-install xsl
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

ADD xdebug.ini /etc/php/conf.d/
ADD xdebug.ini /etc/php/7.4/mods-available/

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

EXPOSE 9000

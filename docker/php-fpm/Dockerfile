FROM php:fpm-alpine

CMD ["php-fpm"]

ARG PUID=1000
ARG PGID=1000

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

RUN apk add --no-cache zip libzip-dev
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup -g ${PGID} mitek && adduser -D -u ${PUID} -G mitek mitek

EXPOSE 9000

WORKDIR /var/www
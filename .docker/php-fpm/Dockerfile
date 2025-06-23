FROM php:8.2.27-fpm-alpine

# Arguments defined in docker-compose.yml
ARG GROUP
ARG UID

# Enviorement
ENV TZ=America/Campo_Grande

COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer

RUN apk --no-cache --update add git curl tzdata shadow imagemagick imagemagick-dev gcc g++ autoconf libpng libpng-dev libjpeg libwebp make pkgconfig libzip libzip-dev \
&& git clone https://github.com/Imagick/imagick.git --depth 1 /tmp/imagick \
&& cd /tmp/imagick \
&& phpize \
&& ./configure \
&& make \
&& make install \
&& docker-php-ext-install pdo pdo_mysql gd \
&& docker-php-ext-enable imagick \
&& usermod -u $UID $GROUP \
&& apk del git imagemagick-dev gcc g++ autoconf libpng-dev make pkgconfig libzip-dev \
&& rm -Rf /var/cache/apk/*
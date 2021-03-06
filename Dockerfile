FROM php:8-cli as runtime

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt update && apt install -y libzip-dev libicu-dev make curl \
    && docker-php-ext-install zip intl \
    && apt remove -y libzip-dev libicu-dev \
    && rm -rf /var/lib/apt/lists/* /tmp/*

# Xdebug install
ARG XDEBUG_VERSION=3.0.3
RUN set -eux; \
    apt update && apt install -y $PHPIZE_DEPS \
	&& pecl install xdebug-$XDEBUG_VERSION \
    && apt remove -y $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/* /tmp/*

FROM runtime as xdebug

RUN docker-php-ext-enable xdebug

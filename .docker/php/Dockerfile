FROM php:7.4-fpm

RUN apt-get update && \
	apt-get upgrade -y && \
	apt-get install -y \
	git \
	libzip-dev \
	zip

RUN docker-php-ext-install pdo_mysql mysqli

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# We're going to use this path multile times. So save it in a variable.
ARG XDEBUG_INI="/usr/local/etc/php/conf.d/xdebug.ini"

# Install AND configure Xdebug
RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug \
	&& echo "[xdebug]" > $XDEBUG_INI \
	&& echo "xdebug.mode = debug" >> $XDEBUG_INI \
	&& echo "xdebug.start_with_request = yes" >> $XDEBUG_INI \
	&& echo "xdebug.client_port = 9003" >> $XDEBUG_INI \
	&& echo "xdebug.client_host = 'host.docker.internal'" >> $XDEBUG_INI \
	&& echo "xdebug.log = /tmp/xdebug.log" >> $XDEBUG_INI


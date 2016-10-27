#!/bin/bash

apt-get update

apt-get install -y \
bison \
autoconf \
build-essential \
pkg-config \
git-core \
libltdl-dev \
libbz2-dev \
libxml2-dev \
libxslt1-dev \
libssl-dev \
libicu-dev \
libpspell-dev \
libenchant-dev \
libmcrypt-dev \
libpng-dev \
libjpeg8-dev \
libfreetype6-dev \
libmysqlclient-dev \
libreadline-dev \
libcurl4-openssl-dev

rm -rf /etc/php7

mkdir -p /etc/php7
mkdir -p /etc/php7/cli
mkdir -p /etc/php7/etc

rm -rf php-src

git clone https://github.com/php/php-src.git --depth=1

cd php-src/ext

git clone https://github.com/krakjoe/pthreads -b master pthreads

cd ..

./buildconf --force

CONFIGURE_STRING="--prefix=/etc/php7 \
--with-bz2 \
--with-zlib \
--enable-zip \
--disable-cgi \
--enable-soap \
--enable-intl \
--with-mcrypt \
--with-openssl \
--with-readline \
--with-curl \
--enable-ftp \
--enable-mysqlnd \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
--enable-sockets \
--enable-pcntl \
--with-pspell \
--with-enchant \
--with-gettext \
--with-gd \
--enable-exif \
--with-jpeg-dir \
--with-png-dir \
--with-freetype-dir \
--with-xsl \
--enable-bcmath \
--enable-mbstring \
--enable-calendar \
--enable-simplexml \
--enable-json \
--enable-hash \
--enable-session \
--enable-xml \
--enable-wddx \
--enable-opcache \
--with-pcre-regex \
--with-config-file-path=/etc/php7/cli \
--with-config-file-scan-dir=/etc/php7/etc \
--enable-cli \
--enable-maintainer-zts \
--with-tsrm-pthreads \
--enable-debug \
--enable-fpm \
--with-fpm-user=www-data \
--with-fpm-group=www-data"

./configure $CONFIGURE_STRING

make && make install

chmod o+x /etc/php7/bin/phpize

chmod o+x /etc/php7/bin/php-config

cd ext/pthreads*

/etc/php7/bin/phpize

./configure --prefix='/etc/php7' --with-libdir='/lib/x86_64-linux-gnu' --enable-pthreads=shared --with-php-config='/etc/php7/bin/php-config'

make && make install

cd ../../
# Install FPM config files

cp -r php.ini-production /etc/php7/cli/php.ini
sed -i 's/;date.timezone =.*/date.timezone = Africa\/Lagos/' /etc/php7/cli/php.ini

cp /etc/php7/etc/php-fpm.conf.default /etc/php7/etc/php-fpm.conf

cp /etc/php7/etc/php-fpm.d/www.conf.default /etc/php7/etc/php-fpm.d/www.conf

cp sapi/fpm/init.d.php-fpm /etc/init.d/php7-fpm

chmod +x /etc/init.d/php7-fpm

sed -i 's/Provides:          php-fpm/Provides:          php7-fpm/' /etc/init.d/php7-fpm

sed -i 's#^php_fpm_BIN=.*#php_fpm_BIN=/usr/sbin/php7-fpm#' /etc/init.d/php7-fpm

sed -i 's#^php_fpm_CONF=.*#php_fpm_CONF=/etc/php7/etc/php-fpm.conf#' /etc/init.d/php7-fpm

sed -i 's#^php_fpm_PID=.*#php_fpm_PID=/var/run/php7-fpm.pid#' /etc/init.d/php7-fpm

cp php.ini-production /etc/php7/cli/php-cli.ini

echo "extension=pthreads.so" > /etc/php7/cli/php-cli.ini

echo "zend_extension=opcache.so" >> /etc/php7/cli/php.ini

ln --symbolic /etc/php7/bin/php /usr/bin/php

ln --symbolic /etc/php7/sbin/php-fpm /usr/sbin/php7-fpm

update-rc.d php7-fpm defaults
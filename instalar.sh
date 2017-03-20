#!/bin/bash

cd $HOME

# DEPENDÊNCIAS

bash GO/dependencias.sh

# LIMPEZA

rm -rf /etc/php7
rm /usr/bin/php
rm /usr/bin/phpize
rm /usr/sbin/php7-fpm

mkdir -p /etc/php7
mkdir -p /etc/php7/cli
mkdir -p /etc/php7/etc

# PHP 7.1.X

rm -rf php-7.1.3

wget http://php.net/distributions/php-7.1.3.tar.gz

tar -xzvf php-7.1.3.tar.gz

cd php-7.1.3

./buildconf --force

./configure --disable-cgi \
--enable-bcmath \
--enable-calendar \
--enable-cli \
--enable-debug \
--enable-exif \
--enable-fpm \
--enable-ftp \
--enable-hash \
--enable-intl \
--enable-json \
--enable-maintainer-zts \
--enable-mbstring \
--enable-mysqlnd \
--enable-opcache \
--enable-pcntl \
--enable-session \
--enable-simplexml \
--enable-soap \
--enable-sockets \
--enable-wddx \
--enable-xml \
--enable-zip \
--prefix='/etc/php7' \
--with-bz2 \
--with-config-file-path='/etc/php7/cli' \
--with-config-file-scan-dir='/etc/php7/etc' \
--with-curl \
--with-enchant \
--with-fpm-group='www-data' \
--with-fpm-user='www-data' \
--with-freetype-dir \
--with-gd \
--with-gettext \
--with-jpeg-dir \
--with-mcrypt \
--with-mysqli='mysqlnd' \
--with-openssl \
--with-pcre-regex \
--with-pdo-mysql='mysqlnd' \
--with-png-dir \
--with-pspell \
--with-readline \
--with-tsrm-pthreads \
--with-xsl \
--with-zlib

make

make install

chmod o+x /etc/php7/bin/phpize

chmod o+x /etc/php7/bin/php-config

cp php.ini-production /etc/php7/cli/php.ini

cp php.ini-production /etc/php7/cli/php-cli.ini

sed -i 's/;date.timezone =.*/date.timezone = America\/Sao_Paulo/' /etc/php7/cli/php.ini

cp /etc/php7/etc/php-fpm.conf.default /etc/php7/etc/php-fpm.conf

cp /etc/php7/etc/php-fpm.d/www.conf.default /etc/php7/etc/php-fpm.d/www.conf

cp sapi/fpm/init.d.php-fpm /etc/init.d/php7-fpm

chmod +x /etc/init.d/php7-fpm

sed -i 's/Provides:          php-fpm/Provides:          php7-fpm/' /etc/init.d/php7-fpm

sed -i 's#^php_fpm_BIN=.*#php_fpm_BIN=/usr/sbin/php7-fpm#' /etc/init.d/php7-fpm

sed -i 's#^php_fpm_CONF=.*#php_fpm_CONF=/etc/php7/etc/php-fpm.conf#' /etc/init.d/php7-fpm

sed -i 's#^php_fpm_PID=.*#php_fpm_PID=/var/run/php7-fpm.pid#' /etc/init.d/php7-fpm

echo "zend_extension=opcache.so" >> /etc/php7/cli/php.ini

ln --symbolic /etc/php7/bin/php /usr/bin/php

ln --symbolic /etc/php7/bin/phpize /usr/bin/phpize

ln --symbolic /etc/php7/sbin/php-fpm /usr/sbin/php7-fpm

update-rc.d php7-fpm defaults

cd ..

# PTHREADS

rm -rf pthreads

git clone https://github.com/krakjoe/pthreads -b master --depth=1

cd pthreads

phpize

./configure --enable-pthreads=shared \
--prefix='/etc/php7' \
--with-libdir='/lib/x86_64-linux-gnu' \
--with-php-config='/etc/php7/bin/php-config'

make

make install

echo "extension=pthreads.so" > /etc/php7/cli/php-cli.ini

cd ..

# PHPREDIS

rm -rf phpredis

git clone https://github.com/phpredis/phpredis.git -b develop --depth=1

cd phpredis

phpize

./configure --with-php-config='/etc/php7/bin/php-config'

make

make install

echo "extension=redis.so" >> /etc/php7/cli/php-cli.ini

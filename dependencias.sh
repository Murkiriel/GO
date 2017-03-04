#!/bin/bash

cd $HOME

# DEPENDÊNCIAS

apt-get update

apt-get install -y \
apcalc \
autoconf \
bison \
build-essential \
git-core \
nodejs \
nodejs-legacy \
npm \
libbz2-dev \
libcurl4-openssl-dev \
libenchant-dev \
libfreetype6-dev \
libicu-dev \
libjpeg8-dev \
libltdl-dev \
libmcrypt-dev \
libmysqlclient-dev \
libpng-dev \
libpspell-dev \
libreadline-dev \
libssl-dev \
libxml2-dev \
libxslt1-dev \
pkg-config \
python-pip \
redis-server

# SUBMODULES

cd GO

rm -rf rastrojs

git pull

git submodule update --init --recursive

# RASTROJS

cd rastrojs

npm install express

npm install

# SINESP CLIENTE

pip install sinesp-client

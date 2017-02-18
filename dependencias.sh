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
redis-server

# SUBMODULES

cd GO

git pull

git submodule update --init --recursive

# RASTROJS

cd rastrojs

npm install

cd ..

# SINESP-API

cd sinesp-api

npm install

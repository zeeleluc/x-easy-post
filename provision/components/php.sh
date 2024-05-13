#!/bin/bash

sudo apt-get install software-properties-common
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install -y php8.3 php8.3-bcmath php8.3-bz2 php8.3-cli php8.3-curl php8.3-intl php-json php8.3-mbstring php8.3-opcache php8.3-soap php8.3-xml php8.3-xsl php8.3-zip libapache2-mod-php8.3 php8.3-mysql php8.3-gd php8.3-imagick

sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
sudo php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
sudo rm composer-setup.php

sudo service apache2 restart

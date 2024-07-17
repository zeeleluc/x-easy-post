#!/bin/bash

apt-get update
apt-get install -y apache2

# Copy the vhost config file
cp /var/www/provision/config/apache/vhosts/x-easy-post.local.conf /etc/apache2/sites-available/x-easy-post.local.conf
cp /var/www/provision/config/apache/vhosts/hypeomatic.local.conf /etc/apache2/sites-available/hypeomatic.local.conf

# Disable the default vhost file
a2dissite 000-default

# Enable our custom vhost file
a2ensite x-easy-post.local.conf
a2ensite hypeomatic.local.conf

# Enable ModRewrite
a2enmod rewrite

# Restart for the changes to take effect
service apache2 restart

# ssl certificate for domain
sudo apt install openssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/x-easy-post.local.key \
    -out /etc/ssl/certs/x-easy-post.local.crt \
    -subj "/C=US/ST=CA/L=San Francisco/O=Example, Inc./OU=IT Department/CN=x-easy-post.local"

sudo a2enmod ssl
sudo systemctl restart apache2

# ssl certificate for domain
sudo apt install openssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/hypeomatic.local.key \
    -out /etc/ssl/certs/hypeomatic.local.crt \
    -subj "/C=US/ST=CA/L=San Francisco/O=Example, Inc./OU=IT Department/CN=hypeomatic.local"

sudo a2enmod ssl
sudo systemctl restart apache2

#!/bin/bash

DBHOST=localhost
DBNAMES=(
    'xeasypost'
)
DBUSER=root
DBPASSWD=root

apt-get update

debconf-set-selections <<< "mysql-server mysql-server/root_password password $DBPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DBPASSWD"

apt-get -y install mysql-server

CMD="mysql -u $DBUSER -p$DBPASSWD -e"
$CMD "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));"

for DBNAME in "${DBNAMES[@]}"
do
  echo "Creating database '$DBNAME'...";
	$CMD "CREATE DATABASE IF NOT EXISTS $DBNAME;"

  echo "Creating user...";
  $CMD "CREATE USER 'root'@'localhost' IDENTIFIED BY 'root';"
  $CMD "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;"
  $CMD "CREATE USER 'root'@'%' IDENTIFIED BY 'root';"
  $CMD "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;"
  $CMD "FLUSH PRIVILEGES;"
done

echo "bind-address...";
sudo sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

echo "Restarting MySql...";
sudo service mysql restart

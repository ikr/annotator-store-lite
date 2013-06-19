#!/bin/bash

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# composer packages installation, DB schema creation, etc.

composer install --dev
cd ./www/demo
# bower install
cd ../..

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cp $DIR/CONFIG.sample.json $DIR/CONFIG.json

touch $DIR/data/db.sqlite && chmod 666 $DIR/data/db.sqlite
php $DIR/scripts/init_db.php
echo 'Empty DB created'

#!/bin/bash

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# composer packages installation, DB schema creation, etc.

composer install --dev

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cp $DIR/CONFIG.sample.json $DIR/CONFIG.json

sqlite3 $DIR/data/db.sqlite ''
php $DIR/scripts/init_db.php
chmod 777 $DIR/data/db.sqlite
echo 'Empty DB created'

# Overview

A backend store for the [Annotator](http://annotateit.org/), based on PHP/Silex and SQLite.

# Installation for production

Sample nginx configuration:

    location /annotator-store-lite/ {
        root /srv/www/annotator-store-lite/www;
        try_files $uri $uri/ @annotatorstore;
    }

    location @annotatorstore {
        fastcgi_pass 127.0.0.1:9000;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME /srv/www/annotator-store-lite/www/index.php;
        fastcgi_param REQUEST_URI $uri;
        rewrite ^/annotator-store-lite/(.*)$ /$1 break;
    }

Configure the Web application's "mount" path:

    $ php ./scripts/init_db.php
    $ cd /srv/www/annotator-store-lite
    $ cp ./CONFIG.sample.json ./CONFIG.json

Edit `CONFIG.json`: set `apiRootUrlWithoutTrailingSlash` to `/annotator-store-lite`.

# Installation for development

## Prerequisites

1. Install [VirtualBox and Vagrant](http://docs.vagrantup.com/v1/docs/getting-started/index.html)

2. Run `vagrant plugin install vagrant-salt`

5. Get the Ubuntu 12.10 Quantal x86_64 base box: `vagrant box add quantal64
   https://github.com/downloads/roderik/VagrantQuantal64Box/quantal64.box`

## Host OS steps

From this project's root run:

    ~/Sandbox/annotator-store-lite(master)$ vagrant up

That will provision the development VM start-up. You'll be asked for the host OS'es root
password. That's fine; vagrant needs it to mount the source directory into the guest OS via
NFS. When the provisioning is done, you can ssh into the guest OS by

    ~/Sandbox/annotator-store-lite(master)$ vagrant ssh

and [continue the installation there](http://memegenerator.net/instance/33516935).

## Guest OS steps

    vagrant@quantal64:/vagrant$ cd /vagrant
    vagrant@quantal64:/vagrant$ ./guest_os_install.sh

## Running the tests

    vagrant@quantal64:/vagrant$ ./vendor/bin/phpunit

# Change log

## v0.1.0 published on 2013-06-08

1. Functional Annotator Store API (without search)
2. A working demo page with the front-end plugged in

# Overview

A backend storage for the [Annotator](http://annotateit.org/), based on PHP/Silex and SQLite. You
can [see the demo](http://okfnlabs.org/annotator/demo/) of how Annotator works.

A single annotator-store-lite instance can be used for multiple annotated Web pages on the same
host. The storage relates the annotations to the page URI-s that they originate from, relying on the
[HTTP Referer](https://en.wikipedia.org/wiki/HTTP_referer) header sent with the AJAX request saving
an annotation object.

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

    location ~ \.php$ {
        return 404;
    }

Add the CONFIG, initialize the database:

    $ cd /srv/www/annotator-store-lite
    $ cp ./CONFIG.sample.json ./CONFIG.json
    $ php ./scripts/init_db.php

`./CONFIG.json`: sets the `apiRootUrlWithoutTrailingSlash` to `/annotator-store-lite` -- the storage
Web application's "mount" path.

Include the `<script>` elements from
[www/demo/index.html](https://github.com/ikr/annotator-store-lite/blob/master/www/demo/index.html#L58)
into your Web page. You'll probably find it easier to use [Bower](http://bower.io) package manager
to fetch all the JS
[dependencies](https://github.com/ikr/annotator-store-lite/blob/master/www/demo/bower.json), just as
the demo page does.

# Installation for development

## Bootstrapping

1. Install [VirtualBox and Vagrant](http://docs.vagrantup.com/v2/docs/getting-started/index.html)

2. Run `~/prj/annotator-store-lite(master)$ vagrant up && vagrant ssh`

## Running the tests

    vagrant@vagrant-ubuntu-raring-64:/vagrant$ ./vendor/bin/phpunit

## Viewing the demo page

    http://localhost:8080/demo/

# Change log

## v0.1.0 published on 2013-06-08

1. Functional Annotator Store API (without search)
2. A working demo page with the front-end plugged in

## v0.2.0 published on 2013-06-19

1. Multi-page support via URI-s and HTTP Referer
2. Use bower for the demo page dependencies
3. Switch to the Ubuntu official 13.04 amd64 box under Vagrant

## v1.0.0 published on 2014-05-05

1. Improved Vagrant/SaltStack setup
2. dbFilePath config value introduced

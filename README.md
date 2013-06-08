# Overview

A backend store for the [Annotator](http://annotateit.org/), based on PHP/Silex and SQLite.

# Installation for production

TBD

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
2. A working demo page with the front-en plugged in

get-composer:
  cmd.run:
    - name: 'curl -sS https://getcomposer.org/installer | php'
    - unless: test -f /usr/local/bin/composer
    - cwd: /root
    - require:
      - pkg: core-packages
      - pkg: php-packages

install-composer:
  cmd.wait:
    - name: mv /root/composer.phar /usr/local/bin/composer
    - cwd: /root
    - watch:
      - cmd: get-composer

/vagrant:
  composer.installed:
    - require:
      - cmd: install-composer

js-demo-modules:
  cmd.run:
    - name: bower install
    - user: vagrant
    - group: vagrant
    - cwd: /vagrant/www/demo
    - unless: test -d /vagrant/www/demo/bower_components
    - require:
      - npm: bower

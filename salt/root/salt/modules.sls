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

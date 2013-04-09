php:
  pkg.installed:
    - pkgs:
      - php5
      - php5-cli
      - php5-curl
      - php5-sqlite

php5-fpm:
  pkg:
    - installed
  service:
    - running
    - enable: True
    - watch:
      - file: /etc/php5/conf.d/custom.ini

/etc/php5/conf.d/custom.ini:
  file.managed:
    - source: salt://php/custom.ini
    - require:
      - pkg: php

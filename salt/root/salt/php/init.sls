php5:
  pkg:
    - installed

php5-cli:
  pkg:
    - installed

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
      - pkg: php5

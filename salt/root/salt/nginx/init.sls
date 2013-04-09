apache2:
  pkg:
    - removed
  service:
    - dead
    - enable: False

nginx:
  pkg:
    - installed
  service:
    - running
    - enable: True
    - require:
      - pkg: nginx
    - watch:
      - file: /etc/nginx/sites-enabled/default

/etc/nginx/sites-enabled/default:
  file.managed:
    - source: salt://nginx/server.conf
    - require:
      - pkg: nginx

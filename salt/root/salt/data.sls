/var/lib/annotator-store-lite:
  file.directory:
    - user: vagrant
    - group: vagrant
    - dir_mode: 777

config-file:
  cmd.run:
    - name: cp ./CONFIG.sample.json ./CONFIG.json
    - user: vagrant
    - group: vagrant
    - cwd: /vagrant
    - unless: test -f ./CONFIG.json

database-file:
  cmd.run:
    - name: touch ./db.sqlite && chmod 666 ./db.sqlite && php /vagrant/scripts/init_db.php
    - user: vagrant
    - group: vagrant
    - cwd: /var/lib/annotator-store-lite
    - unless: test -f ./db.sqlite
    - require:
      - file: /var/lib/annotator-store-lite
      - pkg: php-packages
      - cmd: config-file

/var/lib/annotator-store-lite:
  file.directory:
    - user: vagrant
    - group: vagrant
    - dir_mode: 777

database-file:
  cmd.run:
    - name: touch ./db.sqlite && chmod 666 ./db.sqlite
    - user: vagrant
    - group: vagrant
    - cwd: /var/lib/annotator-store-lite
    - unless: test -f ./db.sqlite
    - require:
      - file: /var/lib/annotator-store-lite

config-file:
  cmd.run:
    - name: cp ./CONFIG.sample.json ./CONFIG.json
    - user: vagrant
    - group: vagrant
    - cwd: /vagrant
    - unless: test -f ./CONFIG.json

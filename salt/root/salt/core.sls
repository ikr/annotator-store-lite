core-packages:
  pkg.installed:
    - pkgs:
      - curl
      - git
      - npm

bower:
  npm.installed:
    - require:
      - pkg: core-packages

node-symlink:
  file.symlink:
    - name: /usr/bin/node
    - target: /usr/bin/nodejs
    - require:
      - pkg: core-packages

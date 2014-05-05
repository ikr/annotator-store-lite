core_packages:
  pkg.installed:
    - pkgs:
      - curl
      - git
      - npm

bower:
  npm.installed:
    - require:
      - pkg: core_packages

node_symlink:
  file.symlink:
    - name: /usr/bin/node
    - target: /usr/bin/nodejs

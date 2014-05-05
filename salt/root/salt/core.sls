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

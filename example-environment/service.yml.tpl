AppWebserver-%VERSION%:
  restart: always
  tty: true
  image: ipunktbs/laravel-nginx:1.9.7_php7_v5
  external_links:
  - mysql/DB-Master:database-master
  labels:
    io.rancher.sidekicks: App-%VERSION%
  volumes_from:
  - App-%VERSION%
  environment:
    DATABASE_NAME: db
    DATABASE_USER: user
    DATABASE_PASSWORD: password
    DB_HOST: database-master
    DB_DATABASE: db
    DB_USERNAME: user
    DB_PASSWORD: passowrd
App-%VERSION%:
  image: user/package:%VERSION%
  command: /bin/dc
  stdin_open: true

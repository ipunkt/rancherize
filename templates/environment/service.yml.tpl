%RANCHER_SERVICE_NAME%-%VERSION%:
  restart: always
  tty: true
  image: ipunktbs/laravel-nginx:1.9.7_php7_v5
  external_links:
  - mysql/DB-Master:database-master
  labels:
    io.rancher.sidekicks: %RANCHER_SERVICE_NAME%App-%VERSION%
    io.rancher.scheduler.affinity:host_label_soft_ne: failover=true
    io.rancher.scheduler.affinity:host_label_ne: apps=true
  volumes_from:
  - %RANCHER_SERVICE_NAME%App-%VERSION%
  environment:
    DATABASE_NAME: db
    DATABASE_USER: user
    DATABASE_PASSWORD: pw
    DB_HOST: database-master
    DB_CONNECTION: mysql
    DB_DATABASE: db
    DB_USERNAME: user
    DB_PASSWORD: pw
%RANCHER_SERVICE_NAME%App-%VERSION%:
  image: %DOCKER_REPOSITORY_USER%/%DOCKER_REPOSITORY_NAME%:%DOCKER_TAG_PREFIX%%VERSION%
  command: /bin/dc
  stdin_open: true
  labels:
    io.rancher.container.start_once: 'true'

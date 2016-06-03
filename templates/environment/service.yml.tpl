%RANCHER_SERVICE_NAME%-%VERSION%:
  restart: always
  tty: true
  image: ipunktbs/laravel-nginx:1.9.7_php7_v5
  external_links:
  - %DB_CONTAINER%:database-master
  labels:
    io.rancher.sidekicks: %RANCHER_SERVICE_NAME%App-%VERSION%
    io.rancher.scheduler.affinity:host_label_soft_ne: failover=true
    io.rancher.scheduler.affinity:host_label_ne: apps=true
  volumes_from:
  - %RANCHER_SERVICE_NAME%App-%VERSION%
  environment:
    DATABASE_NAME: %DB_NAME%
    DATABASE_USER: %DB_USER%
    DATABASE_PASSWORD: %DB_PASSWORD%
    DB_HOST: database-master
    DB_CONNECTION: %DB_CONNECTION%
    DB_DATABASE: %DB_NAME%
    DB_USERNAME: %DB_USER%
    DB_PASSWORD: %DB_PASSWORD%%ENVIRONMENT_DATA%
%RANCHER_SERVICE_NAME%App-%VERSION%:
  image: %DOCKER_REPOSITORY_USER%/%DOCKER_REPOSITORY_NAME%:%DOCKER_TAG_PREFIX%%VERSION%
  command: /bin/dc
  stdin_open: true
  labels:
    io.rancher.container.start_once: 'true'

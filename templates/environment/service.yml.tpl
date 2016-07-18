%RANCHER_SERVICE_NAME%-%VERSION%:
  restart: unless-stopped
  tty: true
  image: ipunktbs/laravel-nginx:1.9.7_php7_v5
  external_links:
  - %DB_CONTAINER%:database-master
  labels:
    io.rancher.sidekicks: %RANCHER_SERVICE_NAME%App-%VERSION%
    io.rancher.scheduler.affinity:host_label_soft_ne: failover=true
    io.rancher.scheduler.affinity:host_label: apps=true
  volumes_from:
  - %RANCHER_SERVICE_NAME%App-%VERSION%
  environment:
    DB_HOST: database-master%ENVIRONMENT_DATA%
%RANCHER_SERVICE_NAME%App-%VERSION%:
  image: %DOCKER_REPOSITORY_USER%/%DOCKER_REPOSITORY_NAME%:%DOCKER_TAG_PREFIX%%VERSION%
  restart: never
  command: /bin/true
  stdin_open: true
  labels:
    io.rancher.container.start_once: 'true'

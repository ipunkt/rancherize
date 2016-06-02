Webserver-%VERSION%:
  restart: always
  tty: true
  image: ipunktbs/laravel-nginx:1.9.7_php7_v5
  external_links:
  - mysql/DB-Master:database-master
  labels:
    io.rancher.sidekicks: WebserverApp-%VERSION%
    io.rancher.scheduler.affinity:host_label_soft_ne: failover=true
    io.rancher.scheduler.affinity:host_label_ne: apps=true
  volumes_from:
  - WebserverApp-%VERSION%
  environment:
    DATABASE_NAME: db
    DATABASE_USER: user
    DATABASE_PASSWORD: pw
    DB_HOST: database-master
    DB_CONNECTION: mysql
    DB_DATABASE: db
    DB_USERNAME: user
    DB_PASSWORD: pw
WebserverApp-%VERSION%:
  image: ipunkt/app:test_%VERSION%
  command: /bin/true
  stdin_open: true
  labels:
    io.rancher.container.start_once: 'true'

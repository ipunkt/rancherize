%RANCHER_SERVICE_NAME%:
  restart: always
  ports:
  - 8080:80
  tty: true
  image: ipunktbs/laravel-nginx:1.9.7_php7_v5
  links:
  - Database:database-master
  volumes:
  - #CODE_DIRECTORY#:/var/www/laravel
  environment:
    DATABASE_NAME: db
    DATABASE_USER: user
    DATABASE_PASSWORD: pw
    DB_HOST: database-master
    DB_DATABASE: db
    DB_USERNAME: user
    DB_PASSWORD: pw
    SERVER_URL: http.test.local
Database:
  restart: always
  tty: true
  image: ipunktbs/mysql-master:v1
  stdin_open: true
  volumes_from:
  - Data-DBMaster
  labels:
    io.rancher.sidekicks: Data-Database
  ports:
  - 3306:3306/tcp
  environment:
    MYSQL_ROOT_PASSWORD: cookies
    REPLICATION_USER: replicationuser
    REPLICATION_PASSWORD: nothing
    DATABASE: db
    USER: suer
    PASSWORD: pw
Data-Database:
  tty: true
  command:
  - cat
  image: ubuntu:14.04
  stdin_open: true
  volumes:
  - /var/lib/mysql

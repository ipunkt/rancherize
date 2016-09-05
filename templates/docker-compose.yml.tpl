%RANCHER_SERVICE_NAME%:
  restart: unless-stopped
  ports:
  - %DEVELOPMENT_PORT%:80
  tty: true
  image: ipunktbs/laravel-nginx:1.9.7_php7_v6-debug
  volumes:
  - #CODE_DIRECTORY#:/var/www/laravel

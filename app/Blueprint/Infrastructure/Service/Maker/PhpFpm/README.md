# PHP Fpm Maker
Creates fpm and command services

## Options
- php.version. PHP Version which is generated. Defaults to 7.0  
  Rancherize brings 7.0, at the time of writing 5.3 and 7.2 are available through rancherize-phpXX plugins
- php.update-backend: Set the `BACKEND` environment variable on the main service to SERVICENAME:9000. Defaults to true.(Disable to start with a local environment)
- php.memory-limit.
- php.post-limit.
- php.upload-file-limit
- php.mail.host
- php.mail.port
- php.mail.auth
- php.mail.username. Defaults to `smtp`
- php.mail.password. Defaults to `smtp`
- php.debug. Defaults to false. Include the xdebug module via docker-compose build.
- php.debug-listener. Defaults to `gethostname()`. Set the xdebug.remote_host.

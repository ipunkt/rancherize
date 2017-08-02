FROM php:7.1-alpine
RUN apk add --no-cache git curl tar
COPY [".", "/opt/rancherize"]
RUN cd /opt/rancherize \
	&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && ./composer.phar install && rm composer.phar
RUN cd /opt/rancherize \
	&& curl -SL "https://github.com/rancher/rancher-compose/releases/download/v0.12.5/rancher-compose-linux-amd64-v0.12.5.tar.gz" | tar xz
ENTRYPOINT ["/bin/sh"]
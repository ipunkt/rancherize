FROM php:7.2-rc-alpine

ARG COMPOSER_HASH=544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061
ARG DOCKER_COMPOSE_VERSION=1.16.1
ARG RANCHER_COMPOSE_VERSION=0.12.5
ARG RANCHERIZE_HOME=/home/rancherize
ARG DEFAULT_EDITOR=vi

LABEL maintainer="b.rang@ipunkt.biz" \
	  version.php=$PHP_VERSION \
	  version.docker-compose=$DOCKER_COMPOSE_VERSION \
	  version.rancher-compose=$RANCHER_COMPOSE_VERSION

# prepare pseudo project directory for npm_modules install
RUN ["mkdir", "$RANCHERIZE_HOME"]
RUN ["chmod", "777", "$RANCHERIZE_HOME"]

# there lies the home
ENV HOME=$RANCHERIZE_HOME

# default editor
ENV EDITOR=$DEFAULT_EDITOR

# install packages
RUN apk update \
	&& apk add --no-cache \
		git \
		docker \
		py-pip \
# install docker-compose
	&& pip install docker-compose==$DOCKER_COMPOSE_VERSION

# load rancher-compose
RUN curl -SL "https://github.com/rancher/rancher-compose/releases/download/v$RANCHER_COMPOSE_VERSION/rancher-compose-linux-amd64-v$RANCHER_COMPOSE_VERSION.tar.gz" \
	| tar xz \
	&& mv rancher-compose-*/rancher-compose /usr/local/bin/ \
	&& cp /usr/local/bin/rancher-compose /usr/local/bin/rancher-compose-$RANCHER_COMPOSE_VERSION

COPY [".", "/opt/rancherize"]
WORKDIR /opt/rancherize

# install composer packages
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '$COMPOSER_HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && ./composer.phar install --no-dev && rm composer.phar

ENTRYPOINT ["/opt/rancherize/rancherize"]
#ENTRYPOINT ["/bin/sh"]

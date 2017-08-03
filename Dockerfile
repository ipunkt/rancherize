ARG PHP_VERSION=7.2-rc
FROM php:${PHP_VERSION}-alpine

ARG PHP_VERSION
ARG DOCKER_COMPOSE_VERSION=1.15.0
ARG RANCHER_COMPOSE_VERSION=v0.12.5
ARG RANCHERIZE_HOME=/home/rancherize
ARG DEFAULT_EDITOR=vi

LABEL maintainer="b.rang@ipunkt.biz" \
	  version.php=$PHP_VERSION \
	  version.docker-compose=$DOCKER_COMPOSE_VERSION \
	  version.rancher-compose=$RANCHER_COMPOSE_VERSION

# prepare pseudo project directory for npm_modules install
RUN ["mkdir", "$RANCHERIZE_HOME"]
RUN ["chmod", "777", "$RANCHERIZE_HOME"]

VOLUME $RANCHERIZE_HOME

# there lies the home
ENV HOME=$RANCHERIZE_HOME

# default editor
ENV EDITOR=DEFAULT_EDITOR

# install packages
RUN apk update \
	&& apk add --no-cache \
		git \
		docker \
		py-pip

COPY [".", "/opt/rancherize"]
WORKDIR /opt/rancherize

# load rancher-compose
RUN curl -SL "https://github.com/rancher/rancher-compose/releases/download/$RANCHER_COMPOSE_VERSION/rancher-compose-linux-amd64-$RANCHER_COMPOSE_VERSION.tar.gz" \
	| tar xz \
	&& mv rancher-compose-$RANCHER_COMPOSE_VERSION/rancher-compose .

# install composer packages
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && ./composer.phar install && rm composer.phar

# install docker-compose
RUN pip install docker-compose==$DOCKER_COMPOSE_VERSION

# change workdir to project
WORKDIR $RANCHERIZE_HOME/project
ENTRYPOINT ["/opt/rancherize/rancherize"]
#ENTRYPOINT ["/bin/sh"]
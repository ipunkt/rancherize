FROM php:7.2-cli-alpine

ARG DOCKER_COMPOSE_VERSION=1.21.2
ARG RANCHER_COMPOSE_VERSION=0.12.5
ARG RANCHER_CLI_VERSION=0.6.9
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
RUN apk add --no-cache \
		git \
		docker \
		py-pip \
		su-exec \
# install docker-compose
	&& pip install docker-compose==$DOCKER_COMPOSE_VERSION

# load rancher-compose
RUN curl -sSL "https://github.com/rancher/rancher-compose/releases/download/v$RANCHER_COMPOSE_VERSION/rancher-compose-linux-amd64-v$RANCHER_COMPOSE_VERSION.tar.gz" \
	| tar xz \
	&& mv rancher-compose-*/rancher-compose /usr/local/bin/ \
	&& cp /usr/local/bin/rancher-compose /usr/local/bin/rancher-compose-$RANCHER_COMPOSE_VERSION


RUN curl -sSL "https://releases.rancher.com/cli/v$RANCHER_CLI_VERSION/rancher-linux-amd64-v$RANCHER_CLI_VERSION.tar.gz" \
	| tar xz \
	&& mv rancher-v*/rancher /usr/local/bin/ \
	&& cp /usr/local/bin/rancher /usr/local/bin/rancher-$RANCHER_CLI_VERSION

COPY ["docker", "/opt/rancherize"]
COPY [".", "/opt/rancherize-package"]
WORKDIR /opt/rancherize

# install composer packages
RUN cd /opt/rancherize-package \
	&& CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD) \
	&& if [ "${CURRENT_BRANCH}" = "HEAD" ] ; then CURRENT_TAG=$(git describe --tags) ; fi \
	&& TARGET_VERSION="dev-${CURRENT_BRANCH} as 2.99.9" \
	&& if [ ! -z "${CURRENT_TAG}" ] ; then TARGET_VERSION=$(git describe --tags) ; fi \
	&& (git fetch --unshallow origin || echo "Not a shallow repository, continuing without fetch") \
	&& cd /opt/rancherize \
	&& sed -i "s/%TARGET_VERSION%/${TARGET_VERSION}/" composer.json  \
	&& curl -sSL "https://gist.githubusercontent.com/justb81/1006b89e41e41e1c848fe91969af7a0b/raw/c12faf968e659356ec1cb53f313e7f8383836be3/getcomposer.sh" | sh \
    && COMPOSER_ALLOW_SUPERUSER=1 ./composer.phar  install \
    && rm composer.phar

COPY docker/plugin_path.php /opt/rancherize/vendor/ipunkt/rancherize/

ENTRYPOINT ["/bin/sh", "/opt/rancherize/docker-entrypoint.sh"]

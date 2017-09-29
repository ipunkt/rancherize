#!/bin/sh

USER="root"
if [ ! -z "$GROUP_ID" ] && [ ! -z "$USER_ID" ] ; then
	deluser rancherize > /dev/null 2>&1
	addgroup -g $GROUP_ID rancherize
	adduser -u $USER_ID -G rancherize -D -s /bin/sh rancherize
	USER="rancherize"
fi

if type "$1" > /dev/null ; then
	su-exec "$USER" $*
	exit $?
fi

su-exec "$USER" php /opt/rancherize/rancherize $@

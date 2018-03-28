#!/bin/sh

export PATH=$PATH:/opt/rancherize/vendor/bin/

if [ "$1" != "init" ] && type "$1" >/dev/null ; then
	exec "$@"
	exit $?
fi

exec rancherize "$@"

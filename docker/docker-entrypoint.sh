#!/bin/sh

export PATH=$PATH:/opt/rancherize/vendor/bin/

if type "$1" >/dev/null ; then
	exec "$@"
	exit $?
fi

exec rancherize "$@"

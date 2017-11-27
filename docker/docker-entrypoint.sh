#!/bin/sh

export PATH=$PATH:/opt/rancherize

if type "$1" >/dev/null ; then
	exec "$@"
	exit $?
fi

exec vendor/bin/rancherize "$@"

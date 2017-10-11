#!/bin/sh

if [ "$1" = "sh" ] ; then
	shift 1
fi

if type "$1" >/dev/null ; then
	exec $*
	exit $?
fi

exec php /opt/rancherize/rancherize $@

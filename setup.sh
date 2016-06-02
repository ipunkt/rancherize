#!/bin/bash

#
# Setup
#
# Initialize an app for use with rancherize
#

#
# collect_data
#
# Ask the user for all necesasry information
#
function collect_data {
	echo "PROJECT_NAME"
	echo 
	echo "The project name is used by docker-compose to identify Containers."
	echo "So if projectA and projectB both have a mysql container named"
	echo "MySQL and share the same PROJECT_NAME then docker-compose will try"
	echo "to reuse the already created container which leads to problems"
	read -e -p "PROJECT_NAME: " PROJECT_NAME

	echo ""
	echo "DOCKER_REPOSITORY_USER"
	echo ""
	echo "The user to which your docker image should be pushed to"
	read -e -p "DOCKER_REPOSITORY_USER: " DOCKER_REPOSITORY_USER

	echo ""
	echo "DOCKER_REPOSITORY_NAME"
	echo ""
	echo "The repository name to which your docker image should be pushed to"
	read -e -p "DOCKER_REPOSITORY_NAME: " DOCKER_REPOSITORY_NAME

	echo ""
	echo "RANCHER_SERVICE_NAME"
	echo ""
	echo ""
	read -e -p "RANCHER_SERVICE_NAME: " RANCHER_SERVICE_NAME
}

#
# do_copy
#
# Create the files based on the users answers
#
function do_copy {

	if [ ! -d deploy ] ; then
		mkdir deploy
	fi

	sed \
		-e "s/%DOCKER_REPOSITORY_USER%/$DOCKER_REPOSITORY_USER/g" \
		-e "s/%DOCKER_REPOSITORY_NAME%/$DOCKER_REPOSITORY_NAME/g" \
		-e "s/%PROJECT_NAME%/$PROJECT_NAME/g" \
		$SCRIPTPATH/templates/config.cfg > deploy/config.cfg

	cp $SCRIPTPATH/templates/Dockerfile .
}

function setup {

	echo "=== Setup wizard ==="

	collect_data

	do_copy
}

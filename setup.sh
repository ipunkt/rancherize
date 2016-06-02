#!/bin/bash

#
# Setup
#
# Initialize an app for use with rancherize
#

function validateConfig {

	MISSING_CONFIG=""
	for VARIABLE in PROJECT_NAME DOCKER_REPOSITORY_USER DOCKER_REPOSITORY_NAME DOCKER_TAG_PREFIX RANCHER_SERVICE_NAME ; do
		eval VALUE=\$$VARIABLE

		if [ -z "$VALUE" ] ; then
			MISSING_CONFIG="$MISSING_CONFIG $VARIABLE"
		fi


	done

	if [ ! -z "$MISSING_CONFIG" ] ; then
		clear
		echo ""
		echo ""
		echo "!! Configuration Value missing for $MISSING_CONFIG !!"
		return 2
	fi

	return 0
}

function validateEnvironment {

	MISSING_CONFIG=""
	for VARIABLE in RANCHER_API_URL RANCHER_STACK_ID RANCHER_ACCESS_KEY RANCHER_SECRET_KEY RANCHER_STACK ; do
		eval VALUE=\$$VARIABLE

		if [ -z "$VALUE" ] ; then
			MISSING_CONFIG="$MISSING_CONFIG $VARIABLE"
		fi

	done

	if [ ! -z "$MISSING_CONFIG" ] ; then

		clear
		echo ""
		echo ""
		echo "Configuration Value missing for $MISSING_CONFIG"
		return 2
	fi

	return 0
}

#
# collect_data
#
# Ask the user for all necesasry information
#
function collect_data {
	echo ""
	echo ""
	echo "====================================================================="
	echo "PROJECT_NAME"
	echo ""
	echo "The project name is used by docker-compose to identify Containers."
	echo "So if projectA and projectB both have a mysql container named"
	echo "MySQL and share the same PROJECT_NAME then docker-compose will try"
	echo "to reuse the already created container which leads to problems"
	echo ""
	read -e -p "PROJECT_NAME: " -i "$PROJECT_NAME" PROJECT_NAME

	echo ""
	echo ""
	echo "====================================================================="
	echo "DOCKER_REPOSITORY_USER"
	echo ""
	echo "The user to which your docker image should be pushed to"
	echo "DOCKER_REPOSITORY_USER/DOCKER_REPOSITORY_NAME:DOCKER_TAG_PREFIX\$VERSION"
	echo ""
	read -e -p "DOCKER_REPOSITORY_USER: " -i "$DOCKER_REPOSITORY_USER" DOCKER_REPOSITORY_USER

	echo ""
	echo ""
	echo "====================================================================="
	echo "DOCKER_REPOSITORY_NAME"
	echo ""
	echo "The repository name to which your docker image should be pushed to"
	echo "DOCKER_REPOSITORY_USER/DOCKER_REPOSITORY_NAME:DOCKER_TAG_PREFIX\$VERSION"
	echo ""
	read -e -p "DOCKER_REPOSITORY_NAME: " -i "$DOCKER_REPOSITORY_NAME" DOCKER_REPOSITORY_NAME

	echo ""
	echo ""
	echo "====================================================================="
	echo "DOCKER_TAG_PREFIX"
	echo ""
	echo "The tag prefix will be prefixed before the version number to make"
	echo "tag:"
	echo "DOCKER_REPOSITORY_USER/DOCKER_REPOSITORY_NAME:DOCKER_TAG_PREFIX\$VERSION"
	echo ""
	read -e -p "DOCKER_TAG_PREFIX: " -i "$DOCKER_TAG_PREFIX" DOCKER_TAG_PREFIX

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_SERVICE_NAME"
	echo ""
	echo "The name of the master service, for web apps this is usualy the"
	echo "Webserver, taking the app data as data-container / sidekick"
	echo ""

	read -e -p "RANCHER_SERVICE_NAME: " -i "$RANCHER_SERVICE_NAME" RANCHER_SERVICE_NAME
}

#
# do_copy
#
# Create the files based on the users answers
#
function do_copy {

	echo ""
	echo ""
	echo "== Writing config =="

	if [ ! -d deploy ] ; then
		echo "Creating deploy directory"
		mkdir deploy
	fi

	for FILE in config.cfg docker-compose.yml.tpl ; do
		echo "Writing $FILE"
		sed \
			-e "s/%PROJECT_NAME%/$PROJECT_NAME/g" \
			-e "s/%DOCKER_REPOSITORY_NAME%/$DOCKER_REPOSITORY_NAME/g" \
			-e "s/%DOCKER_REPOSITORY_USER%/$DOCKER_REPOSITORY_USER/g" \
			-e "s/%DOCKER_TAG_PREFIX%/$DOCKER_TAG_PREFIX/g" \
			-e "s/%RANCHER_SERVICE_NAME%/$RANCHER_SERVICE_NAME/g" \
			$SCRIPTPATH/templates/$FILE > deploy/$FILE
	done


	echo "Writing Dockerfile"
	cp $SCRIPTPATH/templates/Dockerfile .

	echo "== Config written =="
}

function setup {

	clear
	echo ""
	echo ""
	echo "=== Setup wizard ==="

	collect_data
	until validateConfig ; do
		collect_data
	done

	do_copy
}

function collect_environment {

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_API_URL"
	echo ""
	echo "This is the url to the rancher api."
	echo "It can be found in the rancher 'api' tab."
	echo ""
	read -e -p "RANCHER_API_URL: " -i "$RANCHER_API_URL" RANCHER_API_URL

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_ACCESS_KEY"
	echo ""
	echo "This is the username part of an api access token."
	echo "It can be created and viewed in the rancher api tab"
	echo ""
	read -e -p "RANCHER_ACCESS_KEY: " -i "$RANCHER_ACCESS_KEY" RANCHER_ACCESS_KEY

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_SECRET_KEY"
	echo ""
	echo "This is the password part of an api access token."
	echo "It can be created in the rancher api tab."
	echo "It is not possible to retrieve it once the created page is closed."
	echo "Write it down somewhere or create a new token each time you set one here"
	echo ""
	read -e -p "RANCHER_SECRET_KEY: " -i "$RANCHER_SECRET_KEY" RANCHER_SECRET_KEY

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_STACK_ID"
	echo ""
	echo "This is the id of the rancher stack."
	echo "It is necessary to specify this speratly because rancher-compose does"
	echo "not allow downloading the docker-/rancher-compose so it is done through"
	echo "plain wget."
	echo ""
	read -e -p "RANCHER_STACK_ID: " -i "$RANCHER_STACK_ID" RANCHER_STACK_ID

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_STACK"
	echo ""
	echo "This is the rancher stack name to which the app should be deployed."
	echo "It has to exist before deploying to it."
	echo ""
	read -e -p "RANCHER_STACK: " -i "$RANCHER_STACK" RANCHER_STACK

}

function create_environment {

	echo "Creating $ENVIRONMENT"

	local ENVIRONMENT_DIRECTORY="deploy/$ENVIRONMENT"
	if [ ! -d "$ENVIRONMENT_DIRECTORY" ] ; then
		echo "Creating environment $ENVIRONMENT directory"
		mkdir "$ENVIRONMENT_DIRECTORY"
	fi

	for FILE in config.cfg service.yml.tpl scale.yml.tpl ; do
		echo "Writing $FILE"
		local URL=$(echo "$RANCHER_API_URL" | sed -e 's/[\/&]/\\&/g')
		sed \
			-e "s/%PROJECT_NAME%/$PROJECT_NAME/g" \
			-e "s/%DOCKER_REPOSITORY_NAME%/$DOCKER_REPOSITORY_NAME/g" \
			-e "s/%DOCKER_REPOSITORY_USER%/$DOCKER_REPOSITORY_USER/g" \
			-e "s/%DOCKER_TAG_PREFIX%/$DOCKER_TAG_PREFIX/g" \
			-e "s/%RANCHER_SERVICE_NAME%/$RANCHER_SERVICE_NAME/g" \
			-e "s/%RANCHER_API_URL%/$URL/g" \
			-e "s/%RANCHER_ACCESS_KEY%/$RANCHER_ACCESS_KEY/g" \
			-e "s/%RANCHER_SECRET_KEY%/$RANCHER_SECRET_KEY/g" \
			-e "s/%RANCHER_STACK_ID%/$RANCHER_STACK_ID/g" \
			-e "s/%RANCHER_STACK%/$RANCHER_STACK/g" \
			$SCRIPTPATH/templates/environment/$FILE > $ENVIRONMENT_DIRECTORY/$FILE
	done
}

function setup-environment {

	clear

	echo ""
	echo ""
	echo "=== Setup environment wizard ==="

	if ! validateConfig ; then
		echo ""
		echo "Please create a valid setup before setting up deploy environments"
		exit 1
	fi

	if readEnvironmentConfig $ENVIRONMENT > /dev/null ; then
		echo "Reading default values from $ENVIRONMENT"
	else
		echo "Reading default values from $DEFAULT_ENVIRONMENT"
		readEnvironmentConfig $DEFAULT_ENVIRONMENT
	fi

	collect_environment
	until validateEnvironment ; do
		collect_environment
	done

	create_environment
}

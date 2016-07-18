#!/bin/bash

#
# Setup
#
# Initialize an app for use with rancherize
#

function printEnvironmentVariables {
	id=0
	for VARIABLE in ${ENVIRONMENT_VARIABLES[@]} ; do
		echo "- $id: $VARIABLE=${!VARIABLE}"
		let id++
	done
	echo ""
}

function printEnvironmentMenu {

		echo
		echo
		echo "a: add variable"
		echo "c: change variable"
		echo "r: remove variable"
		echo "d: add Database variables"
		echo "l: add Laravel variables"
		echo "q: quit without saving"
		echo "s: save"
		echo

}

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
	echo
	echo "Example:"
	echo "Written on the api page: 'Endpoint: http://server/v1/projects/1a5'"
	echo "API_URL: http://server/v1/projects/1a5"
	echo ""
	read -e -p "RANCHER_API_URL: " -i "$RANCHER_API_URL" RANCHER_API_URL

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_ACCESS_KEY"
	echo ""
	echo "This is the username part of an api access token."
	echo "It can be created in the rancher api tab using the Button"
	echo "'Add Environment Api Key'"
	echo "once created the access_key part can be viewed in the list displayed on"
	echo "the page."
	echo ""
	read -e -p "RANCHER_ACCESS_KEY: " -i "$RANCHER_ACCESS_KEY" RANCHER_ACCESS_KEY

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_SECRET_KEY"
	echo ""
	echo "This is the password part of an api access token."
	echo "It can be created in the rancher api tab using the Button"
	echo "'Add Environment Api Key'"
	echo "It is not possible to retrieve it once the created page is closed."
	echo "Write it down somewhere or create a new token each time you set one here"
	echo ""
	read -e -p "RANCHER_SECRET_KEY: " -i "$RANCHER_SECRET_KEY" RANCHER_SECRET_KEY

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_STACK"
	echo ""
	echo "This is the rancher stack name to which the app should be deployed."
	echo "It has to exist before deploying to it."
	echo ""
	read -e -p "RANCHER_STACK: " -i "$RANCHER_STACK" RANCHER_STACK

	echo ""
	echo ""
	echo "====================================================================="
	echo "RANCHER_STACK_ID"
	echo ""
	echo "This is fundamentaly the same as the RANCHER_STACK name in the previous"
	echo "entry and this redundancy will be removed in the future."
	echo "Currently it is necessary to have this because a simple wget request is"
	echo "used to retrieve the composer.zip file for the stack and this call"
	echo "does not know how to map the name to the necessary id"
	echo ""
	echo "It can be found at the end of the stack url"
	echo "Example:"
	echo "Url: https://server/env/1a5/apps/stacks/1e6"
	echo "STACK_ID: 1e6"
	echo ""
	read -e -p "RANCHER_STACK_ID: " -i "$RANCHER_STACK_ID" RANCHER_STACK_ID

	echo ""
	echo ""
	echo "====================================================================="
	echo "DB_CONTAINER"
	echo ""
	echo "The container which you use to connect to your mysql/mariadb."
	echo "This is done through an external_links entry so the stack has to be specified."
	echo "Having no database container is not supported at this time"
	echo ""
	echo "Example:"
	echo "STACKNAME/CONTAINERNAME"
	echo "mysql/DB-Master"
	echo ""
	read -e -p "DB_CONTAINER: " -i "$DB_CONTAINER" DB_CONTAINER

	local ENVIRONMENT_EDITED=""
	local ACTION=""
	echo ""
	echo ""
	clear
	echo "====================================================================="
	echo "ENVIRONMENT_VARIABLES"
	echo ""
	echo "These variables will be passed to your created container uppon creation."
	echo "Any configuration that changes between different containers should be"
	echo "added here."
	echo ""
	echo "Example:"
	echo "APP_ENV=staging"
	echo "APP_KEY=asdfklasjdfm,an sdlfkjxclkfjasdlkfjasdklfjakldf"
	echo "SOMECONFIG=1.53"
	echo ""
	if [[ ! -v ENVIRONMENT_VARIABLES ]] ; then
		declare -g -a ENVIRONMENT_VARIABLES
	fi
	printEnvironmentVariables
	printEnvironmentMenu

	until [ "$ENVIRONMENT_EDITED" ] ; do
		clear
		printEnvironmentVariables
		printEnvironmentMenu
		echo ""
		read -p "Action: " ACTION

		local VARIABLE_NAME
		local VARIABLE_VALUE
		local DELETE_VARIABLE
		case $ACTION in
			a)
			read -e -p "variable name: " VARIABLE_NAME
			read -e -p "variable value: " VARIABLE_VALUE
			ENVIRONMENT_VARIABLES+=($VARIABLE_NAME)
			eval $VARIABLE_NAME="$VARIABLE_VALUE"
			;;
			c)
			read -e -p "variable number: " VARIABLE_NUMBER
			read -e -p "variable value: " VARIABLE_VALUE
			eval ${ENVIRONMENT_VARIABLES[$VARIABLE_NUMBER]}="$VARIABLE_VALUE"
			;;
			r)
			echo "Removing"
			read -e -p "variable number: " DELETE_VARIABLE
			VARIABLE_NAME=(${ENVIRONMENT_VARIABLES[$DELETE_VARIABLE]})
			ENVIRONMENT_VARIABLES=( ${ENVIRONMENT_VARIABLES[@]/$VARIABLE_NAME} )
			;;
			d)
			echo "Adding the default Laravel5 environment variables for database configuration"
			ENVIRONMENT_VARIABLES+=('DB_CONNECTION' 'DB_HOST' 'DB_DATABASE' 'DB_USERNAME' 'DB_PASSWORD')
			DB_CONNECTION="mysql"
			DB_HOST="database-master"
			;;
			l)
			echo "Adding the default Laravel5 environment variables"
			ENVIRONMENT_VARIABLES+=('APP_KEY' 'APP_ENV' )
			DB_CONNECTION="mysql"
			DB_HOST="database-master"
			;;
			s)
			echo "Finished."
			ENVIRONMENT_EDITED="TRUE"
			;;
			q)
			echo "Quitting without saving."
			local SURE
			read -e -p "Are you sure? (y/N)" SURE
			if [ "$SURE" = "y" ] ; then
				exit 0
			fi
			;;
			*)
			echo "Action $ACTION not recognized."
			;;
		esac

	done
}

function create_environment {

	echo "Creating $ENVIRONMENT"

	local ENVIRONMENT_DIRECTORY="deploy/$ENVIRONMENT"
	if [ ! -d "$ENVIRONMENT_DIRECTORY" ] ; then
		echo "Creating environment $ENVIRONMENT directory"
		mkdir "$ENVIRONMENT_DIRECTORY"
	fi

	local ENV_VARS="${ENVIRONMENT_VARIABLES[@]}"
	local ENV_VAR_VALUES=""
	for VARIABLE in ${ENVIRONMENT_VARIABLES[@]} ; do
		ENV_VAR_VALUES+="$VARIABLE=\"${!VARIABLE}\"\n"
	done

	local ENV_YAML=""
	for VARIABLE in ${ENVIRONMENT_VARIABLES[@]} ; do
		ENV_YAML+="\n    $VARIABLE: \"${!VARIABLE}\""
	done

	for FILE in config.cfg service.yml.tpl scale.yml.tpl ; do
		echo "Writing $FILE"

		local TEMPLATEFILE="$SCRIPTPATH/templates/environment/$FILE"
		local ALTERNATIVE_TEMPLATEFILE="deploy/templates/environment/$FILE"
		if [ -f "$ALTERNATIVE_TEMPLATEFILE" ] ; then
			TEMPLATEFILE="$ALTERNATIVE_TEMPLATEFILE"
		fi


		sed \
			-e "s~%PROJECT_NAME%~$PROJECT_NAME~g" \
			-e "s~%DOCKER_REPOSITORY_NAME%~$DOCKER_REPOSITORY_NAME~g" \
			-e "s~%DOCKER_REPOSITORY_USER%~$DOCKER_REPOSITORY_USER~g" \
			-e "s~%DOCKER_TAG_PREFIX%~$DOCKER_TAG_PREFIX~g" \
			-e "s~%RANCHER_SERVICE_NAME%~$RANCHER_SERVICE_NAME~g" \
			-e "s~%RANCHER_API_URL%~$RANCHER_API_URL~g" \
			-e "s~%RANCHER_ACCESS_KEY%~$RANCHER_ACCESS_KEY~g" \
			-e "s~%RANCHER_SECRET_KEY%~$RANCHER_SECRET_KEY~g" \
			-e "s~%RANCHER_STACK_ID%~$RANCHER_STACK_ID~g" \
			-e "s~%RANCHER_STACK%~$RANCHER_STACK~g" \
			-e "s~%DB_CONTAINER%~$DB_CONTAINER~g" \
			-e "s~%USE_DB%~$USE_DB~g" \
			-e "s~%ENVIRONMENT_VARIABLES%~$ENV_VARS~g" \
			-e "s~%ENVIRONMENT_VALUES%~$ENV_VAR_VALUES~g" \
			-e "s~%ENVIRONMENT_DATA%~$ENV_YAML~g" \
			"$TEMPLATEFILE" > "$ENVIRONMENT_DIRECTORY/$FILE"
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

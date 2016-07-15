#!/bin/bash

#
# Show Usage info
# Sourced by rancherize
#

function usage {
	if [ "$#" -ge "1"  ] ; then
		case $1 in
			start)
				echo "Start development environment"
				echo
				echo "Usage: $0 start [path/to/source]"
				echo
				echo "The start command attempts to start the project on your local machine through docker"
				echo "The path given to the command will be mounted into the environment as the app to run"
				echo "Most commonly the [path] will be '.'"
				echo
				echo "The path can be omitted after specifying it once. The last path used is remembered and"
				echo "will be used"
				return 0
				;;
			recreate)
				echo "Recreate the development environment"
				echo
				echo "Usage: $0 recreate [path/to/source]"
				echo
				echo "The recreate command is for convenience. It is equal to calling"
				echo "rancherize stop -rm && rancherize start [path/to/source]"
				return 0
				;;
			stop)
				echo "Stop development environment"
				echo
				echo "Usage $0 stop [-rm]"
				echo
				echo "The stop command attempts to stop the project on your local machine."
				echo "If -rm is passed to stop then the stopped containers will be removed."
				echo
				echo "it is currently equal to doing a manual docker-compose stop"
				return 0
				;;
			test-start)
				echo "Start test environment"
				echo
				echo "Usage: $0 start [revision] [environment]"
				echo
				echo "The start-test environment attempts to start a test environment for the given revision and environment."
				echo "It works not unlike the start command, except the started containers will be tagged by revision and"
				echo "environment and they won't publish any ports."
				echo "This ensures that multiple test environments can run on a deployment server at the same time without"
				echo "one of them to error while trying to bind on a port."
				echo
				echo "Use the test-command to run a command inside the running test environment to run tests"
				return 0
				;;
			test-command)
				echo "Run command in test environment"
				echo
				echo "Usage: $0 test-command COMMAND"
				echo
				echo "Runs the given command in the app directory inside the test environment."
				echo "The return value of the command will be returned by $0"
				return 0
				;;
			test-stop)
				echo "Stop test environment"
				echo
				echo "Usage: $0 test-stop"
				echo
				echo "Stop the currently running test environment"
				return 0
				;;
			artisan)
				echo "Run artisan command inside container"
				echo
				echo "Usage $0 artisan [parameters]"
				echo
				echo "The artisan command will attempt to run the artisan php script inside the development"
				echo "container. Any arguments given here will be passed on to artisan"
				return 0
				;;
			logs)
				echo "Show stdout & stderr from the development container"
				echo
				echo "Usage $0 logs [parameters]"
				echo
				echo "The logs command will run 'docker logs' on the web server container of the development"
				echo "environment."
				echo "Any parameters given will be passed on to docker logs."
				echo
				echo "Noteworthy:"
				echo "  -f: Continue to watch the log as it is written, like tail -f"
				return 0
				;;
			commit)
				echo "Commit the current source as image to docker"
				echo
				echo "Usage $0 commit [tag]"
				echo
				echo "The commit command will build your current source directory as image for docker."
				echo "If no tag is given then the current svn revision prefixed by r is used. (ex. r312)"
				echo "If svn info shows added, modified or deleted files then the default tag will be suffixed by 'c'"
				echo "(ex. r321c)"
				return 0
				;;
			deploy)
				echo "Deploy given tag to the rancher stack"
				echo
				echo "Usage $0 deploy [environment] [tag]"
				echo
				echo "The deploy command will attempt to create the app in rancher"
				echo "for the first time using the tag given."
				echo
				echo "[environment]"
				echo "If no environment is specified then 'staging' is used."
				echo
				echo "[tag]"
				echo "The tag specifies which version should be online after the rolling upgrade"
				echo "more specifically the image with tag $DOCKER_TAG_PREFIX[tag] will be used"
				echo "If no tag is specified then the last one built using the commit command is used"
				return 0
				;;
			upgrade)
				echo "Upgrade given tag to the rancher environment"
				echo
				echo "Usage $0 upgrade [environment] [tag]"
				echo
				echo "The upgrade command will attempt to do a rolling upgrade from the current version to the"
				echo "image with the specified tag."
				echo
				echo "[environment]"
				echo "If no environment is specified then 'staging' is used."
				echo
				echo "[tag]"
				echo "The tag specifies which version should be online after the rolling upgrade"
				echo "more specifically the image with tag $DOCKER_TAG_PREFIX[tag] will be used"
				echo "If no tag is specified then the last one built using the commit command is used"
				return 0
				;;
			pull-up)
				echo "Upgrade given tag to the rancher environment"
				echo
				echo "Usage $0 pull-up [source-environment] [target-environment]"
				echo
				echo "The pull-up command will attempt to retrieve the current active revision from the source-environment"
				echo "and then upgrade the target-environment to this revision"
				echo
				return 0
				;;
			setup)
				echo "Setup your app for development and docker container building"
				echo
				echo "Usage $0 setup"
				echo
				echo "The setup will ask you a list of questions related to the project and the docker repository its"
				echo "images should be published to."
				echo
				echo "If you already have configuration present then its values will be presented to you as default."
				return 0
				;;
			setup-environment)
				echo "Setup your app for deploy to a rancher environment"
				echo
				echo "Usage $0 setup-environment [environment] [default-environment]"
				echo
				echo "The setup will ask you a list of questions related to the rancher environment, stack and api"
				echo
				echo "If you already have this environment present then its values will be presented to you as default."
				echo "If the environment is not present yet then the values from the default-environment will be"
				echo "presented as default."
				echo
				echo "If [environment] or [default-environment] are not given then they will default to 'staging'"
				return 0
				;;
			*)
				echo "No help for command '$1' found"
				echo
				;;
		esac
	fi

	echo "Usage: $0 [COMMAND] [OPTIONS]"
	echo
	echo "Commands:"
	echo "- development"
	echo "  start             - Start docker environment"
	echo "  stop              - Stops docker environment"
	echo "  recreate          - Stop and delete the current environment, then"
	echo "                      start a clean one"
	echo "  artisan           - Passes artisan commands through docker exec"
	echo "  logs              - Displays the stdout & stderr of the container"
	echo "- testing"
	echo "  test-start        - Starts a new test environment, by default for the current svn revision and environment staging"
	echo "  test-command      - Runs a command in the Web container of the currently running test environemnt"
	echo "  test-stop         - Stops the currently running test environment"
	echo "- deploy"
	echo "  commit            - Build a new data image and commit it to docker. Default Tag $PROJECT_PREFIX"REVISION
	echo "  deploy            - Deploys an image to rancher. Defaults to the last image built by commit"
	echo "  upgrade           - Upgrades the service in rancher to the given revision. Defaults to the last image built by commit"
	echo "  pull-up           - Retrieves the revision from one environment and upgrades the second environment to it"
	echo "  revision          - Shows the currently detected Version for this directory"
	echo "- setup"
	echo "  setup             - Setup your app for local development and docker image building"
	echo "  setup-environment - Setup a rancher deploy target"
	echo "  help              - Show help for each command listed here"
	echo
	echo "For more specific help please use the help command"
	echo " $0 help [command]"
}


# Rancherize
Rancherize is a php cli script based on symfony/console. It makes developing with docker and rancher easy for developers
without specialized knowledge in the subjects.
This is done by choosing a blueprint that fits your apps required environment and setting abstract requirements instead
of of adding and connecting services.

For a concrete example on how the configuration becomes easier through this see the example at the bottom of this page.

# Installation
Rancherize is installed using composer

	composer require ipunkt/rancherize:v2.*@dev
	
# Configuration

## Accounts

Rancherize knows 2 types of accounts: 

- docker accounts. They are used to push images to docker hub
- rancher accounts. They are used to deploy your app to your rancher environment

Both are managed in the json file `~/.rancherize` which should be set to be only readable by your own user.  
For easy editing use the following command. It opens the file in your `$EDITOR` and creates a default file
if it does not exist yet.


	vendor/bin/rancherize rancher:access
	
## Environments

Rancherize configuration is split into `environments`. A typical app knows at least a `local` and a `production`
environment. Environments are configured by editing the file `rancherize.json` inside the app work directory.

Note that all configuration values can also be set in the `defaults` section. Values in this section will be used if the
configuration value does not appear in the `environment`
 
The command `init` can be used to create an initial configuration for an environment.  
It will prompt the blueprint to create a sensible default production configuration. If the `--dev` Flag is used then
a configuration for a local development environment is created instead.


	vendor/bin/rancherize init [--dev] BLUEPRINT ENVIRONENTNAME1 ENVIRONEMNTNAME2... ENVIRONMENTNAMEX
	
	e.g.
	vendor/bin/rancherize init --dev webserver local
	vendor/bin/rancherize init webserver production staging


### Set Environment Variable
The command `environment:set` exists to conveniently set an environment value for all `environments`. It will go through
all `environments`, display the current value and ask for the new value. If none is given then the old value
will be used again.

	vendor/bin/rancherize environment:set VARIABLENAME

	e.g.
	vendor/bin/rancherize environment:set APP_KEY
	
## Development Environment

The command `start` exists to start an environment of your app on the local machine.



	vendor/bin/rancherize start ENVIRONMENTNAME
	
	e.g.
	vendor/bin/rancherize start local
	
Note that this command does not currently build a docker image from your work directory so the `environment` should be
set to mount your work directory directly. For the WebserverBlueprint this means setting

- `"use-app-container": false`
- `"mount-workdir":"true"`

Theses settings are included when initializing with the `--dev` flag

## Deploy
The command `push` exists to deploy the current state of your work directory into Rancher.  

	vendor/bin/rancherize push ENVIRONEMNT VERSION
	
	e.g.
	vendor/bin/rancherize push staging v1

- The current state of your work directory is build as docker image and tagged as
`$(docker.repository):$(docker.version-prefix)VERSION`  
- The built Image is pushed to docker hub using the credentials from the global configuration named `$(docker.account)`
- The current configuration of the stack in rancher is retrieved
  - If the stack does not exist yet it is created empty
- The apps configuration is added to the stack configuration
- The app is deployed into the stack
  - If no other version of the service is found it will be created
  - If the same version of the service is found then an in-service upgrade is triggered
  - If a different version of the service is found then a rolling-upgrade ist triggered
  
# Blueprints

## Known blueprints

Currently only the [WebserverBlueprint](app/Blueprint/Webserver/README.md) is available. 

## Developing Blueprints

See the [blueprint readme](app/Blueprint/README.md) for more information on how to develop your own blueprints

# Example
```yaml
Database:
  image: 'mysql/mysql'
  tty: true
  environment:
    MYSQL_ROOT_PASSWORD: root
  stdin_open: true
  restart: unless-stopped
PMA:
  image: 'phpmyadmin/phpmyadmin:4.6.2-3'
  tty: true
  ports:
    - '8082:80'
  stdin_open: true
  links:
    - 'Database:db'
  labels:
    db: null
  restart: unless-stopped
```

becomes 

```json
{
	"example-environment": {
		"add-database": true
	}
}
```

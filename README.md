# rancherize

[![Latest Stable Version](https://poser.pugx.org/ipunkt/rancherize/v/stable.svg)](https://packagist.org/packages/ipunkt/rancherize) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/rancherize/v/unstable.svg)](https://packagist.org/packages/ipunkt/rancherize) [![License](https://poser.pugx.org/ipunkt/rancherize/license.svg)](https://packagist.org/packages/ipunkt/rancherize) [![Total Downloads](https://poser.pugx.org/ipunkt/rancherize/downloads.svg)](https://packagist.org/packages/ipunkt/rancherize)

Rancherize your development workflow.

Rancherize is currently specialized in deploying php apps with a database server, namely
laravel apps. It will learn to support you with more features for your container
as the need arises

## Features
- Start a development webserver for your local development environment
- Build and publish a new docker image
- Deploy your last published version to a rancher stack
- Perform a deploy to a rancher stack to start your app there
- Perform a rolling upgrade in rancher to your lastest published version
- Setup wizard for easy project setup
- Setup wizard for easy environment setup

## Install
Install via composer:

```
  composer require "ipunkt/rancherize:*@dev"
```

## Usage
### Initial setup and local environment

The `setup` command will ask you docker related questions to set up your repository and your local devolopment environment

Start with

```
  vendor/bin/rancherize setup
```

The setup will ask for your docker repositories and project name to set up your
local development environment

Once this is done you should be able to start your environment with 

```
  vendor/bin/rancherize start
```

This will create an nginx serving your app with a database on your localhost
prot 8080.
To stop it, run

```
  vendor/bin/rancherize stop
```
  
### Rancher deploy
The `setup-environment` command will ask you rancher related questions to set up your deploy to a rancher environment.
The following has to be done before or during this wizard:
- Have a database server ready
- Add a stack in rancher to which the app should be deployed
- Create an api key in rancher which will be used to do the deploy

After creating the environment using the wizard you will be able to deploy your last commited app version to rancher
using the following command

```
  vendor/bin/rancherize deploy {environment} {version}
```
  
If you wish to do a rolling upgrade to a new version then use the following command

```
  vendor/bin/rancherize upgrade {environment} {version}
```

### Customization
Rancherize does not yet support everything rancher can do. If you wish to use these features then you'll have to edit
your docker-compose.yml by hand.

The following steps are taken to generate the finale docker-compose.yml

#### Local environemnt
1. `rancherize setup`
  - templates/docker-compose.yml.tpl => deploy/docker-compose.yml.tpl
2. `rancherize start`
  - deploy/docker-compose.yml.tpl => deploy/docker-compose.yml

#### Rancher environment
1. `rancherize setup-environment`
  - templates/environment/service.yml.tpl => deploy/${ENVIRONMENT}/docker-compose.yml.tpl
  - templates/environment/scale.yml.tpl => deploy/${ENVIRONMENT}/rancher-compose.yml.tpl
2. `rancherize deploy` / `rancherize upgrade`
  - deploy/docker-compose.yml.tpl => deploy/docker-compose.yml

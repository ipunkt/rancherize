# rancherize2
Rancherize your development workflow - in php

# Installation
	composer require ipunkt/rancherize
	
# Use
	vendor/bin/rancherize rancher:access
	vendor/bin/rancherize rancher:init webserver production staging
	vendor/bin/rancherize rancher:init --dev webserver local

# Configuration

## Global
Edit the global Configuration using the command

	rancherize rancher:access
	
### Format
The File is written in json and if it does not exist yet a default is created for you.

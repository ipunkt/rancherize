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

  composer require "ipunkt/rancherize:*@dev"

## Usage
Start with

  vendor/bin/rancherize setup

The setup will ask for your docker repositories and project name to set up your
local development environment

Once this is done you should be able to start your environment with 

  vendor/bin/rancherize start

#!/usr/bin/env bash

docker run -it -v $HOME/.rancherize:/home/rancherize/.rancherize -v /var/run/docker.sock:/var/run/docker.sock -v $(pwd):$(pwd) -w $(pwd) -e "USER_ID=$(id -u)" -e "GROUP_ID=$(id -g)" ipunktbs/rancherize $*
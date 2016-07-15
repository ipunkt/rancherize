#!/bin/bash

########################################################################################################################
# upgrade_lib.sh
#
# Author: Sven Speckmaier
# Datum: 07.01.2016
#
# Funktionen um herauszufinden welcher Service eines Rancher Stacks aktuell aktiv ist.
#
########################################################################################################################

if [ "$SCRIPTPATH" = "" ] ; then
	pushd `dirname $0` > /dev/null
	SCRIPTPATH=`pwd`
	popd > /dev/null
fi

#
# Läd die docker-compose.yml und rancher-compose.yml für den in den Parametern angegebenen Rancher-Stack in das aktuelle
# Verzeichnis
#
# Parameter:
# 1. Benutzername: in Rancher ACCESS_KEY genannt. Variable RANCHER_ACCESS_KEY in docker-compose
# 2. Passwort: in Rancher SECRET_KEY genannt. Variable RANCHER_SECREKT_KEY in docker-compose
# 3. Url: Die Url zur Rancher API. Variable RACHER_RUL in docker-compose
# 4. ENVIRONMENT: Die Id des Rancher Stacks. Wird in docker-compose standartmäßig aus dem Verzeichnisnamen gelesen
#                   TODO: Die Id sollte hier aus dem Namen aufgelöst werden, dann kann Id oder Name übergeben werden
#
function update_compose {
	USER=$1
	PASSWORD=$2
	URL=$3
	ENVIRONMENT=$4

	MAXIMUM_ATTEMPTS=5
	ATTEMPTS=0
	until wget -q --user $USER --password $PASSWORD $URL/environments/$ENVIRONMENT/composeconfig -O compose.zip ; do
		let ATTEMPTS+=1

		if [ "$ATTEMPTS" -gt "$MAXIMUM_ATTEMPTS" ]  ; then
			echo "Update compose schlug mehr als $MAXIMUM_ATTEMPTS mal fehl: abbruch"
			return 1
		fi

		echo "Update compose fehlgeschlagen, warte 1s"
		sleep 1s
	done
	unzip -qq -o compose.zip
	return 0
}

#
# Such in der rancher-compose.yml Datei alle Services folgende Eigenschaften haben:
# - scale > 0
# - Servicename matcht Regex $STACKNAME-r[0-9]*
#
# Parameter:
# 1. Stackname: für die Regex
#
function find_version {

	NAME=$1

	php $SCRIPTPATH/filter_scale_larger_zero.php $NAME'-r[0-9]*'
	echo

	return

}

#
# Fügt eine neue Version des Services zur docker-compose.yml Datei hinzu.
# Dazu wird eine Template-Datei benötigt in der der Platzhalter VERSION ersetzt wird.
#
# Parameter:
# 1. SERVICE_NAME: Mit SERVICE_NAME-VERSION wird geprüft ob die Version nicht schon hinterlegt ist.
# 2. VERSION: Der Platzhalter %VERSION% in der Template Datei wird hiermit ersetzt
# 3. Template: Die Template-Datei aus der die neue Version erzeugt wird. Wenn nicht angegeben: "service.yml.tpl"
#
function add_version {

	SERVICE_NAME=$1
	SVN_VERSION=$2
	TEMPLATE=service.yml.tpl
	if [ "$#" -ge "3" ] ; then
		TEMPLATE=$3
	fi

	if ! grep $SERVICE_NAME-$SVN_VERSION docker-compose.yml > /dev/null 2>&1 ; then
		sed "s/%VERSION%/$SVN_VERSION/g" $TEMPLATE >> docker-compose.yml
	fi

	return

}


#!/bin/sh

DIRECTORY_PROJECT=/var/www/villa-gonatouki/nuxt-modern-website
DIRECTORY_RESOURCES=/var/www/villa-gonatouki/nuxt-modern-website/public

### CETTE COMMANDE EST EXECUTER LORS DE MISE A JOUR NUXTJS
commandRsyncStatic="rsync -au $DIRECTORY_PROJECT/dist/static/*/ $DIRECTORY_PROJECT/production-build/static/*/"
commandRsyncProductionBuildToDist="rsync -au $DIRECTORY_PROJECT/production-build/ $DIRECTORY_PROJECT/dist/"
commandChmod="chmod -Rf 777 $DIRECTORY_PROJECT/.nuxt/ $DIRECTORY_PROJECT/dist/ $DIRECTORY_PROJECT/build_routes/"
commandRmProductionBuild="rm -Rf $DIRECTORY_PROJECT/production-build"
commandRmJSFiles="rm $DIRECTORY_RESOURCES/*.js"
commandMoveJSFiles="mv $DIRECTORY_PROJECT/dist/*.js $DIRECTORY_RESOURCES/"
commandRmFolderFonts="rm -Rf $DIRECTORY_RESOURCES/fonts"
commandMoveFolderFonts="mv $DIRECTORY_PROJECT/dist/fonts $DIRECTORY_RESOURCES/fonts"
commandMoveFolderStatic="mv $DIRECTORY_PROJECT/dist/static $DIRECTORY_RESOURCES/static"
commandRmWaitingFiles="rm -f $DIRECTORY_PROJECT/build_routes/waiting/*"

### on se déplace en premier dans le dossier view $1 = path
if [ ! -z "$1" ]
then
    cd $1
fi

command="yarn run generate --no-build --no-lock --fail-on-error --entity webPage --path /web_pages/accueil --route index --slug '/'"

## run command ##
$command

## check exit code ##
if [ $? -eq 0 ]
then
	$commandRsyncStatic
	$commandRsyncProductionBuildToDist
	$commandRmJSFiles
    	$commandMoveJSFiles
	echo "$commandMoveJSFiles"
    	#$commandRmFolderFonts
    	#$commandMoveFolderFonts
	$commandRmProductionBuild
	$commandRmWaitingFiles
	$commandChmod

	#echo "$command command was successful $?" | mail -s "Build production root to L'immobilière d'Essaouira" johan13.remy@gmail.com
	echo "$command command was successful $?"
else
	$commandChmod
	$commandRmProductionBuild
    echo "Command failed : $command" | mail -s "Build production root to L'immobilière d'Essaouira" johan13.remy@gmail.com -c irina.essaouira@gmail.com
    echo "Command failed : $command"
    exit 1
fi
exit 0


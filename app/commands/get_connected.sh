#!/bin/bash
# Returning connected clients

#inject config
dir=$(dirname "$(readlink -f "$0")")
source $dir/config.sh

case "$1" in 
	"-n") 
		number=$(create_ap --list-clients ap0 | wc -l) 
		echo $((number - 1))
	;;
	
	*) 
	echo "TODO CLIENTLIST"
	;;
esac
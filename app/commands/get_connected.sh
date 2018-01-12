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
		if [ $(create_ap --list-running | wc -l) -ge 3 ]
		then
			create_ap --list-clients ap0
		else
			echo "Hotspot not running"
		fi
	;;
esac
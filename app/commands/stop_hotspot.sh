#!/bin/bash
# Creating hotspot

#inject config
dir=$(dirname "$(readlink -f "$0")")
source $dir/config.sh

if [ $(create_ap --list-running | wc -l) -ge 3 ]
then
	create_ap --stop ap0
fi

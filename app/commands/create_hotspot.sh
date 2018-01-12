#!/bin/bash
# Removing connection for the given interface (in0, in1, all)

#inject config
dir=$(dirname "$(readlink -f "$0")")
source $dir/config.sh

if [ "$1" = "" ]
then
  	echo "ERROR: SSID was not specified."
	exit 0
fi

source $dir/stop_hotspot.sh
create_ap "$WLOUT0" bond0 "$1" "$2"
sleep 10s
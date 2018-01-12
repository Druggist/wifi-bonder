#!/bin/bash
# Get avaible wifi networks for the given interface (in0, in1)

#inject config
dir=$(dirname "$(readlink -f "$0")")
source $dir/config.sh

interface=""

if [ "$1" = "in0" ]; then
  interface=$WLIN0
elif [ "$1" = "in1" ]; then
  interface=$WLIN1
else
	echo "ERROR: Interaface was not specified."
	exit 0
fi

echo `nmcli -t -f ssid,in-use dev wifi list ifname "$interface" | awk -F':' '$2 == "*" {print $1}'`

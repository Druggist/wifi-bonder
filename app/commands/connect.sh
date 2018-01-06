#!/bin/bash
# Connecting to the given SSID any password with specified interface (in0, in1)
#example: ./connect.sh in0 SSID password

#inject config
dir=$(dirname "$(readlink -f "$0")")
source $dir/config.sh

interface=""

if [ "$1" = "in0" ]
then
  interface=$WLIN0
elif [ "$1" = "in1" ]
then
  interface=$WLIN1
else
	echo "ERROR: Interaface was not specified."
	exit 0
fi

if [ "$2" = "" ]
then
  	echo "ERROR: SSID was not specified."
	exit 0
fi

tmp=$(nmcli -t -f name,device con show --active | awk -F':' -v wlin="$interface" '$2 == wlin {print $1}')
if [ "$tmp"  != "" ]
	then
	dir=$(dirname "$(readlink -f "$0")")
	$dir/disconnect.sh "$1"
	sleep 5s
fi
nmcli dev wifi connect "$2" password "$3" ifname "$interface"
#nmcli con modify "$2" master bond0 
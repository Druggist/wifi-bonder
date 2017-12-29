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
  	echo "ERROR: Interaface was not specified."
	exit 0
fi

name=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)
tmp=$(nmcli con show --active | awk -v wlin="$interface" '$4 == wlin {print $1}')
if [ "$tmp"  != "" ]
	then
	dir=$(dirname "$(readlink -f "$0")")
	$dir/disconnect.sh "$1"
	sleep 5s
fi

nmcli dev wifi connect "$2" password "$3" ifname "$interface" name "$name"
#!/bin/bash
# Removing connection for the given interface (in0, in1, all)

#inject config
dir=$(dirname "$(readlink -f "$0")")
source $dir/config.sh

case "$1" in 
	"in0") 
		con=$(nmcli -t -f name,device con show --active | awk -F':' -v wlin="$WLIN0" '$2 == wlin {print $1}')
		nmcli con delete "$con"
	;;
	
	"in1")
		con=$(nmcli -t -f name,device con show --active | awk -F':' -v wlin="$WLIN1" '$2 == wlin {print $1}')
		nmcli con delete "$con"
	;;

	"all")	
		con=$(nmcli -t -f name,device con show --active | awk -F':' -v wlin="$WLIN0" '$2 == wlin {print $1}')
		nmcli con delete "$con"
		con=$(nmcli -t -f name,device con show --active | awk -F':' -v wlin="$WLIN1" '$2 == wlin {print $1}')
		nmcli con delete "$con"
	;;
	
	*) 
	echo "ERROR: Wrong interface"
	;;
esac

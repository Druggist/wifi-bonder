<?php 
	exec('sudo commands/create_hotspot.sh "'.$_GET["ssid"].'" "'.$_GET["pass"].'"', $out, $res);
	echo "<br> out: ";
	var_dump($out);
	echo "<br> res: ";
	var_dump($res);
?>
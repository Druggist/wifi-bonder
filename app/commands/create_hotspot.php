<?php 
	if (array_key_exists(1, $argv)) {
		$ssid = $argv[1];
		$pass = "";
		if (array_key_exists(2, $argv)) $pass = $argv[2];

		echo exec('sudo commands/create_hotspot.sh '.$ssid.' '.$pass.' > /dev/null 2>/dev/null &', $out, $res);
		sleep(10);
		var_dump($out);
		var_dump($res);
	}

?>
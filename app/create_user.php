<?php
	require_once 'core/init.php';
	$salt = substr(Hash::salt(64), 0, 64);
	$hash = Hash::make('zaq12wsx', $salt);
	$db = DB::getInstance();
	if($db->insert('groups', array(
		'title' => 'admin',
		'data' => ""
	))) {
		$groupId = $db->insertId();
		if($db->insert('users', array(
		'username' => 'root',
		'passwordhash' => $hash,
		'passwordsalt' => $salt,
		'groupid' => $groupId))) {
			echo 'success';
		} else {
			echo 'fail to add user';
		}
	} else {
		echo 'fail to add group';
	}
?>
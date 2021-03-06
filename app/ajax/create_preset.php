<?php chdir('..');
require_once 'core/init.php';
$user = new User();
if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}
$db = DB::getInstance();

if(Input::exists('post')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'preset_name' => array(
			'required' => true
		)));   		
	if(!$validation->passed()) { 
		die("Preset name not passed");
	}
	if ($db->query('INSERT INTO `networkgroups` (`name`, `userid`) VALUES ("'.Input::get('preset_name').'", '.$user->data()->userid.')')->error()) {
		die("Cannot insert network group");
	}
	$networkGroupId = $db->insertId();
	if ($db->query('UPDATE configs SET networkgroupid='.$networkGroupId.' WHERE userid='.$user->data()->userid)->error()) {
		die('Cannot update user config');
	}
	$db->insert('logs', array(
				'type' => 0,
				'description' => "Created preset ".Input::get('preset_name'),
				'userid' => $user->data()->userid
				));
	Redirect::to('../networks.php');		
} else {
	die("Network group id needed");
} ?>
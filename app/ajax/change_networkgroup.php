<?php chdir('..');
require_once 'core/init.php';
$user = new User();
if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}
$db = DB::getInstance();

if(Input::exists('get')) {
	$validate = new Validate();
	$validation = $validate->check($_GET, array(
		'networkgroupid' => array(
			'required' => true
		)));   		
	if(!$validation->passed()) { 
		die("Network group id not passed");
	}
	if(Input::get('networkgroupid') == "none") {
		Redirect::to('../networks.php');
	}
	if ($db->query('UPDATE configs SET networkgroupid='.Input::get('networkgroupid').' WHERE userid='.$user->data()->userid)->error()) {
		die("Cannot update networkgroupid");
	}
	if ($db->query('SELECT * FROM networks WHERE type="I" AND networkgroupid='.Input::get('networkgroupid'))->error()) {
		die("Cannot fetch networks for group");
	}
	$networks = $db->results();
	if($db->count() >0) {
		exec('sudo commands/connect.sh "in0" "'.$networks[0]->ssid.'" "'.$networks[0]->password.'"', $out, $res);
		echo "conn 1<br>";
	}
	if($db->count() >1) {
		exec('sudo commands/connect.sh "in1" "'.$networks[1]->ssid.'" "'.$networks[1]->password.'"', $out, $res);
		echo "conn 2";
	}		
	Redirect::to('../networks.php');		
} else {
	die("Network group id needed");
} ?>
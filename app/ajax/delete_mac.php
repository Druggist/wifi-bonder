<?php chdir('..');
require_once 'core/init.php';
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

$messages = array();
$db = DB::getInstance();
if(Input::exists('get')){
	$validate = new Validate();
	$validation = $validate->check($_GET, array(
		'id' => array(
			'required' => true,
			'type' => 'number'
		)));   		
	if($validation->passed()) {		
		$id = Input::get('id');
		if($db->query("DELETE FROM allowedmacs WHERE macid = $id")->error()) {
		    die('Deletion error!');
		}
	} else {
		foreach($validation->errors() as $error) {
			array_push($messages, $error);
		} 	
	}				
}
Redirect::to('../dhcp.php'); ?>
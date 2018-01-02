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
		if($db->query("SELECT * FROM pastes WHERE pasteid = $id")->error()) {
		    die('Wystąpił problem z pobraniem danych!');
		}
		if(count($db->results()) < 1) {
			die("Nie ma pasty o podanym id!");
		}
	} else {
		foreach($validation->errors() as $error) {
			array_push($messages, $error);
		}
	}				
}
$result = $db->results()[0];

echo "<h4>".$result->title."</h4>";
echo "<span>".$result->creationtime."</span>";
echo "<p>".$result->data."</p>"; ?>
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
		$result = $db->results()[0];
		if ($db->query("SELECT getdaystodelete(".Input::get('id').") as days")->error()) {
			die ("Cannot fetch days to delete paste.");
		}
		$days = $db->results()[0]->days;
	} else {
		foreach($validation->errors() as $error) {
			array_push($messages, $error);
		} 	
	}				
}

echo "<h4>Title: ".$result->title."</h4>";
echo "<span>Creation time: ".$result->creationtime."</span>";
echo "<br><span>Expiration time: ".$days."</span>";
echo "<p><b>Text:</b><br>".$result->data."</p>";
echo "" ?>
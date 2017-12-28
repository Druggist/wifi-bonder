<?php require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}
$messages = array();
$db = DB::getInstance();

If(Input::exists()){	  
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'title' => array(
			'required' => true, 
			'min' => 2,
			'max' => 100
		),
		'data' => array( 
			'required' => true,
			'min' => 0,
			'max' => 1000
		)
	));   		
	if($validation->passed()) {						
		$data = Input::get('data');				
		$title = Input::get('title');			
		$flag = Input::get('flag');
		if($flag!='') {
			$flag='1';
		} else {
			$flag='0';
		}
		$userid = 3;//$user->data->userid;

		if(!$db->insert('pastes', array(
			'data' => $data,
			'title' => $title,
			'flag' => $flag,
			'userid' => 3
			))) {
			die('Wystąpił problem z zapisywaniem danych!');
		}		
	} else {
		foreach($validation->errors() as $error) {
			array_push($messages, $error);
		}
	}
}
 
if($db->query("SELECT * FROM pastes ORDER BY `creationtime` DESC")->error()){
    die('Wystąpił problem z pobraniem danych!');
}
$results = $db->results();
print_r($messages); ?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="icon" href="static/img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="static/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="static/css/main.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="#{author}">
    <meta name="description" content="#{description}">
    <meta name="keywords" content="#{keywords}">
    <title>WiFi bonder app</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col s12 m6 offset-m3">
          <div class="card">
            <div class="card-content center">
              <div class="card-title">PASTY</div>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <div class="row">
                <div class="col s12 center"><a href="ajax/create_paste.php">Create new paste</a></div>
              </div><?php foreach($results as $result) {
	echo "<a href=ajax/show_paste.php?id=".$result->pasteid.">".$result->title . " " . $result->creationtime . "</a><br>";
}  ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
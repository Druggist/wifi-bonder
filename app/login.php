<?php require_once 'core/init.php';

$messages = array();
$user = new User();

if($user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {   		  
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'email' => array(
				'required' => true, 
				'min' => 5,
				'max' => 254, 
				'type' => 'email'
			),
			'haslo' => array( 
				'required' => true,
				'min' => 4,
				'max' => 50
			)
		));   		
		if($validation->passed()) {						
			$remember = (Input::get('rememberme') === 'on') ? true : false;
			$login = $user->login(Input::get('email'), Input::get('haslo'), $remember);
			if($login) {
				Redirect::to('index.php');
			} else {
				array_push($messages, "Niepoprawne hasło lub email!");
			}				
		} else {
			foreach($validation->errors() as $error) {
				array_push($messages, $error);
			}
		}
	} 
	echo $message;
}
 ?><!DOCTYPE html>
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
  </body>
</html>
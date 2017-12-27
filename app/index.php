<?php require_once 'core/init.php';

$messages = array();
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

if(Session::exists('success')) {
	array_push($messages, Session::flash('success'));
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
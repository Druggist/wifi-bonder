<?php require_once 'core/init.php';

$messages = array();
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

$db = DB::getInstance();
if ($db->query('SELECT * FROM logs ORDER BY logid DESC')->error()) {
	die("Failed to fetch logs");
}
$logs = $db->results();
 ?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="icon" href="static/img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="static/img/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="static/css/main.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="#{author}">
    <meta name="description" content="#{description}">
    <meta name="keywords" content="#{keywords}">
    <title>WiFi bonder app</title>
  </head>
  <body>
    <ul class="side-nav" id="slide-out">
      <li>
        <div class="divider"></div><a class="subheader">Administration</a>
      </li>
      <li><a class="waves-effect" href="index.php"><i class="material-icons">device_hub</i>Hub</a></li>
      <li><a class="waves-effect" href="networks.php"><i class="material-icons">network_wifi</i>Networks</a></li>
      <li><a class="waves-effect" href="dhcp.php"><i class="material-icons">dns</i>DHCP</a></li>
      <li><a class="waves-effect" href="performance.php"><i class="material-icons">network_check</i>Performance</a></li>
      <li><a class="waves-effect" href="logs.php"><i class="material-icons">error</i>Logs</a></li>
      <li><a class="waves-effect" href="logout.php"><i class="material-icons">exit_to_app</i>Log out</a></li>
      <li>
        <div class="divider"></div><a class="subheader">Services</a>
      </li>
      <li><a class="waves-effect" href="pastes.php"><i class="material-icons">content_paste</i>Pastes</a></li>
    </ul><a class="button-collapse show-on-large menu btn waves-effect btn-large white" href="#" data-activates="slide-out"><i class="material-icons">menu</i></a>
    <div class="container">
      <div class="row">
        <div class="col s12">
          <div class="card">
            <div class="card-content center">
              <div class="card-title">Logs</div>
            </div>
          </div>
        </div>
        <div class="col s12">
          <ul class="collapsible card" data-collapsible="accordion"><?php foreach($logs as $log) {
	if($log->type==0) {
		$type = "info";
	} elseif ($log->type==1) {
		$type = "warning";
	} else {
		$type = "error";
	}
	$username = new User($log->userid);
	$username = $username->data()->username;
	echo '<li>
		<div class="collapsible-header '.$type.'">
			<i class="material-icons">'.$type.'</i><span>'.$log->date.'</span>
		</div>
		<div class="collapsible-body">
			<span><b>User: </b> '.$username.'</span><br>
			<span><b>Type: </b> '.$type.'</span><br>
			<span><b>Date: </b> '.$log->date.'</span><br>
			<span><b>Message: </b><i>'.$log->description.'</i></span>
		</div>
	</li>';
}
 ?>
          </ul>
        </div>
      </div>
    </div>
    <script src="components/jquery/dist/jquery.min.js"></script>
    <script src="components/materialize/dist/js/materialize.min.js"></script>
    <script src="static/js/main.min.js"></script>
    <script src="static/js/logs.min.js"></script>
  </body>
</html>
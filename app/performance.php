<?php require_once 'core/init.php';

$messages = array();
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

$db = DB::getInstance();
if ($db->query('SELECT performances.networkid, ssid, name FROM ((performances LEFT JOIN networks ON performances.networkid = networks.networkid) LEFT JOIN networkgroups ON networks.networkgroupid = networkgroups.networkgroupid) GROUP BY performances.networkid')->error()) {
	die("Failed to fetch logs");
}
$testGroups = $db->results();

if(Input::exists('get')) {
	$validate = new Validate();
	$validation = $validate->check($_GET, array(
		'id' => array(
			'required' => true,
			'type' => 'number'
		)
	));   

	if($validation->passed()) { 
		if ($db->query('SELECT * FROM performances WHERE networkid='.Input::get('id'))->error()) {
			die("Failed to fetch specified network performances");
		}
		$tests = $db->results();
	} else {
		foreach($validation->errors() as $error) {
			array_push($messages, $error);
		}
	}
	
} else {
	$tests = array();
}
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
              <div class="card-title">Performance</div>
            </div>
          </div>
        </div>
        <div class="col s12">
          <div class="card">
            <div class="card-content no-spaces">
              <div class="card-title center">Tests</div>
              <form method="get">
                <div class="row no-spaces">
                  <div class="input-field col s12 m12 l12">
                    <select id="id" name="id">
                      <option value="" selected disabled><?php foreach($testGroups as $group) {
	echo '<option value="'.$group->networkid.'">'.$group->ssid.' at preset: '.$group->name.'</option>';
} ?>
                      </option>
                    </select>
                    <label>Select network</label>
                  </div>
                </div>
              </form>
            </div>
            <ul class="collapsible" data-collapsible="accordion"><?php foreach($tests as $test) {
echo '<li>
		<div class="collapsible-header"><span>'.$test->testdate.'</span></div>
		<div class="collapsible-body">
			<div class="wifi row">
				<div class="performance col s6">
					<div class="title">Download</div>'.$test->downloadspeed.' kb/s
				</div>
				<div class="performance col s6">
					<div class="title">Upload</div>'.$test->uploadspeed.' kb/s
				</div>
			</div>
		</div>
	</li>';
} ?>
            </ul>
          </div>
        </div>
        <div class="col s12">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">New test
                <form method="post">
                  <div class="row">
                    <div class="input-field col s12 m4 offset-m3 right-align">
                      <select>
                        <option value="" selected disabled></option>
                        <option value="1">In1</option>
                        <option value="2">In2</option>
                        <option value="3">Out</option>
                      </select>
                      <label>Select network</label>
                    </div>
                    <div class="input-field col s12 m4 left-align"><a class="btn waves-effect waves-light" href="#">Test</a></div>
                  </div>
                </form>
                <div class="wifi row">
                  <div class="performance col s6">
                    <div class="title">Download</div>126 kb/s
                  </div>
                  <div class="performance col s6">
                    <div class="title">Upload</div>126 kb/s
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="components/jquery/dist/jquery.min.js"></script>
    <script src="components/materialize/dist/js/materialize.min.js"></script>
    <script src="static/js/main.min.js"></script>
    <script src="static/js/performance.min.js"></script>
  </body>
</html>
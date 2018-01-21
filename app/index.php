<?php require_once 'core/init.php';

$messages = array();
$user = new User();
$db = DB::getInstance();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

if(Session::exists('success')) {
	array_push($messages, Session::flash('success'));
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {   
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'oldpass' => array(
				'required' => true,
				'min' => 6,
				'max' => 50
			),
			'newpass' => array(
				'required' => true,
				'min' => 6,
				'max' => 50
			),
			'repass' => array(
				'required' => true,
				'min' => 6, 
				'max' => 50,
				'matches' => 'newpass' 
			) 
		));   

		if($validation->passed()) { 
			if(Hash::make(Input::get('oldpass'), $user->data()->passwordsalt) !== $user->data()->passwordhash) {
				array_push($messages, "Wrong password");
			} else {
				$salt = Hash::salt(32);
				$hash = Hash::make(Input::get('newpass'), $salt);
				try{                  
					$user->update(array(
						'passwordhash' => $hash,
						'passwordsalt' => $salt
					));    
					Session::flash('success', 'Password changed');
					Redirect::to('index.php');
				} catch (Exception $e) {
					die($e->getMessage());
				}
			}
		} else {
			foreach($validation->errors() as $error) {
				array_push($messages, $error);
			}
		}
	}
}

if ($db->query('SELECT `networkgroupid`, `networkid` FROM `configs` WHERE userid='.$user->data()->userid)->error()){
	die('Failed to fetch user config!');
}
$userConfig = $db->results()[0];

$inputNetworks = array();
if ($userConfig->networkgroupid != null) {
	if ($db->query('SELECT `networkid`, `ssid` FROM `networks` WHERE `networkgroupid`='.$userConfig->networkgroupid.' AND `type`="I"')->error()) {
		die('Failed to fetch input networks!');
	}
	$inputNetworks = $db->results();
}

if ($db->query('SELECT `networkid`, `ssid`, `password` FROM `networks` WHERE `networkid`='.$userConfig->networkid.' AND `type`="O"')->error()) {
	die('Failed to fetch output networks!');
}
$outputNetwork=$db->results()[0];

foreach($messages as $error) 
echo $error
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
              <div class="card-title">Hub</div>
            </div>
          </div>
        </div>
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">IN</div>
              <div class="wifi row">
                <div class="ssid center col s12">
                   <?php if(count($inputNetworks) > 0)
	echo $inputNetworks[0]->ssid;  ?>
                </div>
              </div>
              <div class="wifi row">
                <div class="ssid center col s12">
                   <?php if(count($inputNetworks) > 1)
	echo $inputNetworks[1]->ssid; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">OUT</div>
              <div class="wifi row">
                <div class="ssid center col s12">
                   <?php echo $outputNetwork->ssid; ?></div>
                <div class="pass center col s12">Password: <span>
                     <?php echo $outputNetwork->password; ?></span></div>
                <div class="devices col s12">Connected devices:<span>
                     <?php echo exec('sudo commands/get_connected.sh -n'); ?></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">User</div>
              <div class="user center">
                <div class="name">
                   <?php echo $user->data()->username; ?></div>
                <div class="group">
                   <?php echo $user->group_data()->title; ?></div>
                <div class="joined">Joined:<span>
                     <?php echo $user->data()->joined; ?></span></div>
                <div class="row">
                  <div class="col s12 m6"><a class="btn waves-effect waves-light modal-trigger" href="#changepass">Change password </a></div>
                  <div class="col s12 m6"><a class="btn waves-effect waves-light" href="logout.php">Log out</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="changepass">
      <div class="modal-content">
        <h4>Change password</h4>
        <form method="post">
          <div class="row">
            <div class="input-field col s12">
              <input id="oldpass" type="password" name="oldpass">
              <label for="oldpass">Old password</label>
            </div>
            <div class="input-field col s12">
              <input id="newpass" type="password" name="newpass">
              <label for="newpass">Password</label>
            </div>
            <div class="input-field col s12">
              <input id="repass" type="password" name="repass">
              <label for="repass">Confirm password</label>
            </div>
            <div class="col s12 center">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
              <button class="btn waves-effect waves-light" type="submit">Change</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer"><a class="modal-action modal-close waves-effect waves-light btn-flat" href="#!">Close		</a></div>
    </div>
    <script src="components/jquery/dist/jquery.min.js"></script>
    <script src="components/materialize/dist/js/materialize.min.js"></script>
    <script src="static/js/main.min.js"></script>
    <script src="static/js/index.min.js"></script>
  </body>
</html>
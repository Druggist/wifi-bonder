<?php require_once 'core/init.php';

$messages = array();
$db = DB::getInstance();
$user = new User();	
if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

if ($db->query('SELECT * FROM configs WHERE userid='.$user->data()->userid)->error()) {
	die('Failed to get users config!');
}
$userConfig = $db->results()[0];

if(Input::exists()) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'ssid' => array(
			'required' => true
		),
		'iface' => array(
			'required' => true
		)));
	if($validation->passed()) { 
		exec('commands/get_ssid.sh '.Input::get('iface'), $old_ssid);
		exec('sudo commands/connect.sh "'.Input::get('iface').'" "'.Input::get('ssid').'" "'.Input::get('pass').'"', $out, $res);
		if($res == 0) {
			if ($userConfig->networkgroupid != null) {
				if($old_ssid[0] != "") {
					if ($db->query('DELETE FROM `networks` WHERE `ssid`="'.$old_ssid[0].'" AND `networkgroupid`='.$userConfig->networkgroupid.' LIMIT 1')->error()){
						die("Could not delete old connection!");
					}
				}
				if ($db->query('INSERT INTO `networks` (`ssid`, `password`, `type`, `networkgroupid`) VALUES ("'.Input::get('ssid').'", "'.Input::get('pass').'", "I", '.$userConfig->networkgroupid.')')->error()) {
					die("cannot insert network to database");
				}
			}
			array_push($messages, "Connected to ".Input::get('ssid')." on interface ".Input::get('iface'));
		} else {
			array_push($messages, "Could not connect to ".Input::get('ssid'));
		}
	} else {
		foreach($validation->errors() as $error) {
			echo $error;
		}
	}
}

if ($db->query('SELECT * FROM networks WHERE networkid='.$userConfig->networkid)->error()) {
	die('Failed to get user output network!');
}
$outputNetwork = $db->results()[0];

if ($db->query('SELECT * FROM networkgroups WHERE userid='.$user->data()->userid)->error()) {
	die('Failed to fetch user saved network groups!');
}
$networkGroups = $db->results();

exec("./commands/get_ssid.sh in0", $ssid);
exec("./commands/get_ssid.sh in1", $ssid);

$inputNetworks = array();
if($userConfig->networkgroupid != null) {
	if ($db->query('SELECT * FROM networks WHERE networkgroupid='.$userConfig->networkgroupid.' AND type="I"')->error()) {
		die('Failed to fetch user input networks!');
	}
	foreach($db->results() as $network) {
		if(in_array($network->ssid, $ssid)) {
			array_push($inputNetworks, $network);
		}
	}
	if(count($inputNetworks) < $db->count()) {
		if($db->query("UPDATE configs SET networkgroupid=null WHERE userid=".$user->data()->userid)->error()) {
			die('Failed to update user config!');
		}
		$userConfig->networkgroupid=null;
		array_push($messages, "Could not connect to netwroks from preset.");
	} else {
		array_push($messages, "Succesfully connected to networks from preset.");
	}
} ?><!DOCTYPE html>
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
        <div class="col s12 m12">
          <div class="card">
            <div class="card-content center">
              <div class="card-title">NETWORKING</div>
            </div>
          </div>
        </div>
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content center">
              <div class="card-title">IN</div>
              <form method="get" action="ajax/change_networkgroup.php">
                <div class="row">
                  <div class="input-field col s12 m8">
                    <select id="network_groups" name="networkgroupid"><?php echo '<option value="none"></option>';
foreach($networkGroups as $group) {
	if ($group->networkgroupid == $userConfig->networkgroupid) {
		echo '<option value="'.$group->networkgroupid.'" selected>'.$group->name.'</option>';	
	} else {
		echo '<option value="'.$group->networkgroupid.'">'.$group->name.'</option>';
	}
} ?>
                    </select>
                    <label>Load preset</label>
                  </div>
                  <div class="input-field col s12 m4">
                    <button class="btn waves-effect waves-light" type="submit">Load</button>
                  </div>
                  <div class="input-field col s12"><a class="btn waves-effect waves-light modal-trigger" href="#create_preset">New</a></div>
                </div>
              </form>
              <div class="row">
                <div class="input-field col s12"><a class="modal-trigger" href="#show_networks" data-iface="in0">
                    <input id="in0" type="text" name="in0" value="<?php echo $ssid[0]; ?>" disabled>
                    <label for="in0">Interface 1</label></a></div>
              </div>
              <div class="row">
                <div class="input-field col s12"><a class="modal-trigger" href="#show_networks" data-iface="in1">
                    <input id="in1" type="text" name="in1" value="<?php echo $ssid[1]; ?>" disabled>
                    <label for="wl1">Interface 2</label></a></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content center"> 
              <div class="card-title">OUT</div>
              <form method="post">
                <div class="row">
                  <div class="input-field col s12">
                    <input id="ssid" type="text" name="ssid" value="<?php echo $outputNetwork->ssid; ?>">
                    <label for="ssid">Ssid</label>
                  </div>
                  <div class="input-field col s12">
                    <input id="password" type="password" name="password" value="<?php echo $outputNetwork->password; ?>">
                    <label for="password">Password</label>
                  </div>
                  <div class="col s12 center">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <button class="btn waves-effect waves-light" type="submit">UPDATE</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="show_networks">
      <div class="modal-content" id="networks_content">
        <h4>Available networks</h4>
      </div>
      <div class="modal-footer"><a class="modal-action modal-close waves-effect waves-light btn-flat" href="#!">Close	</a></div>
    </div>
    <div class="modal modal-pass" id="connect">
      <div class="modal-content">
        <h4>Connect</h4>
        <form method="post">
          <div class="row">
            <div class="input-field col s12">
              <input id="connect_ssid" type="text" name="ssid">
              <input id="connect_iface" type="text" name="iface">
              <label for="connect_ssid">SSID</label>
            </div>
            <div class="input-field col s12">
              <input id="pass" type="password" name="pass">
              <label for="connect_pass">Password</label>
            </div>
            <div class="col s12 center">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
              <button class="btn waves-effect waves-light" type="submit">Connect</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer"><a class="modal-action modal-close waves-effect waves-light btn-flat" href="#!">Close		</a></div>
    </div>
    <div class="modal" id="create_preset">
      <div class="modal-content">
        <h4>Save preset</h4>
        <form method="post" action="ajax/create_preset.php">
          <div class="row">
            <div class="input-field col s12">
              <input id="preset_name" type="text" name="preset_name">
              <label for="preset_name">Name</label>
            </div>
            <div class="col s12 center">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
              <button class="btn waves-effect waves-light" type="submit">Save</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer"><a class="modal-action modal-close waves-effect waves-light btn-flat" href="#!">Close			</a></div>
    </div>
    <script src="components/jquery/dist/jquery.js"></script>
    <script src="components/materialize/dist/js/materialize.js"></script>
    <script src="static/js/networks.js"></script><?php if(!empty($messages)){
	echo '<script type="text/javascript">';
	foreach ($messages as $message) {
		echo "Materialize.toast('{$message}', 4000);";
	}
	echo '</script>';
}

				 ?>
  </body>
</html>
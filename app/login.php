<?php require_once 'core/init.php';

$db = DB::getInstance();
$messages = array();
$user = new User();

if($user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {   		  
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true, 
				'min' => 2,
				'max' => 100
			),
			'pass' => array( 
				'required' => true,
				'min' => 6,
				'max' => 50
			)
		));   		
		if($validation->passed()) {						
			$remember = (Input::get('rememberme') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('pass'), $remember);
			$userId = $user->data()->userid;
			if($login){
				if($db->query('SELECT * FROM configs WHERE userid='.$userId)->error()) {
					die('Failed to fetch user config!');
				}
				if($db->count() < 1) {
					if($db->insert('networkgroups', array(
							"name" => "Default network group",
							"userid" => $userId))) {
						$networkGroupId = $db->insertId();
					} else {
						die('Failed to create default network group!');
					}
					if($db->insert('networks', array(
							"ssid" => "Default output network",
							"password" => "password",
							"type" => "O",
							"networkgroupid" => null))) {
						$networkId = $db->insertId();
					} else {
						die('Failed to create default output network!');
					}
					if(!($db->insert('networks', array(
							"ssid" => "Default input network1",
							"password" => "password",
							"type" => "I",
							"networkgroupid" => $networkGroupId
						)) && $db->insert('networks', array(
							"ssid" => "Default input network2",
							"password" => "password",
							"type" => "I",
							"networkgroupid" => $networkGroupId
						)))) {
						die('Failed to create default input networks!');
					}
					if(!$db->insert('configs', array(
							"userid" => $userId,
							"networkgroupid" => $networkGroupId,
							"networkid" => $networkId
						))) {
						die('Failed to create default users config');
					}
				} 
				Redirect::to('index.php');
			} else {
				array_push($messages, "Wrong username or password!");
			}				
		} else {
			foreach($validation->errors() as $error) {
				array_push($messages, $error);
			}
		}
	}
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
    <div class="container">
      <div class="row">
        <div class="col s12 m6 offset-m3">
          <div class="card">
            <div class="card-content center">
              <div class="card-title">LOG IN</div>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <form method="post">
                <div class="row">
                  <div class="input-field col s12">
                    <input id="username" type="text" name="username">
                    <label for="username">Username</label>
                  </div>
                  <div class="input-field col s12">
                    <input id="pass" type="password" name="pass">
                    <label for="pass">Password</label>
                  </div>
                  <div class="col s12">
                    <input id="rememberme" type="checkbox" name="rememberme">
                    <label for="rememberme">Remember me</label>
                  </div>
                  <div class="col s12 center">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <button class="btn waves-effect waves-light" type="submit">Log in</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="components/jquery/dist/jquery.min.js"></script>
    <script src="components/materialize/dist/js/materialize.min.js"></script>
    <script src="static/js/main.min.js"></script><?php if(!empty($messages)){
	echo '<script type="text/javascript">';
	foreach ($messages as $message) {
		echo "Materialize.toast('{$message}', 4000);";
	}
	echo '</script>';
} ?>
  </body>
</html>
<?php require_once 'core/init.php';

$messages = array();
$db = DB::getInstance();
$user = new User();	
// if(!$user->isLoggedIn()) {
// 	Redirect::to('login.php');
// }

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
		echo Input::get('iface').'<br>';
		echo Input::get('ssid').'<br>';
		echo Input::get('password').'<br>';
		echo 'dupa';
		echo exec('commands/connect.sh '.Input::get('iface').' '.Input::get('ssid').' '.Input::get('password'));
		echo 'koniec';
	} else {
		foreach($validation->errors() as $error) {
			echo $error;
		}
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
              <div class="card-title">OUT</div>
              <form method="post">
                <div class="row">
                  <div class="input-field col s12">
                    <input id="ssid" type="text" name="ssid">
                    <label for="ssid">Ssid</label>
                  </div>
                  <div class="input-field col s12">
                    <input id="password" type="password" name="password">
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
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content center">
              <div class="card-title">IN</div>
              <table class="highlight">
                <thead> 
                  <tr>
                    <th>Inteface</th>
                    <th>Ip</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>IFACE 1</td>
                    <td>IP 1</td>
                    <td> <a class="btn modal-trigger" href="#show_networks" iface="in0">EDIT</a></td>
                  </tr>
                  <tr>
                    <td>IFACE 2</td>
                    <td>IP 2</td>
                    <td> <a class="btn modal-trigger" href="#show_networks" iface="in1">EDIT</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="show_networks">
      <div class="modal-content" id="networks_content">
        <h4>Available networks</h4>
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
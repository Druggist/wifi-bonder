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
              <div class="wifi">
                <div class="ssid center col s12">TEMP</div>
                <div class="performance col s6">
                  <div class="title">Download</div>126 kb/s
                </div>
                <div class="performance col s6">
                  <div class="title">Upload</div>126 kb/s
                </div>
              </div>
              <div class="wifi row">
                <div class="ssid center col s12">TEMP</div>
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
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">OUT</div>
              <div class="wifi row">
                <div class="ssid center col s12">TEMP</div>
                <div class="pass center col s12">Password: <span>$sfaewfg!3</span></div>
                <div class="devices col s12">Connected devices:<span>6</span></div>
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
        <div class="col s12">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">User</div>
              <div class="user center">
                <div class="name">Duda</div>
                <div class="group">Admin</div>
                <div class="joined">Joined:<span>20-03-1993 12:32:02</span></div>
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
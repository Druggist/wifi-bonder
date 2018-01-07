<?php require_once 'core/init.php';

$messages = array();
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
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
              <div class="card-title">DHCP</div>
            </div>
          </div>
        </div>
        <div class="col s12">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">Connected devices</div>
              <ul class="collection">
                <li class="collection-item">
                  <div class="connected"><span>TEMP_COMPUTER</span><span>23:23:34:ff:02:4d</span><span>192.168.1.1</span></div>
                </li>
                <li class="collection-item">
                  <div class="connected"><span>TEMP_COMPUTER</span><span>23:23:34:ff:02:4d</span><span>192.168.1.1</span></div>
                </li>
                <li class="collection-item">
                  <div class="connected"><span>TEMP_COMPUTER</span><span>23:23:34:ff:02:4d</span><span>192.168.1.1</span></div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">Banned MACs</div>
              <ul class="collection">
                <li class="collection-item">
                  <div class="connected">23:23:34:ff:02:4d<a class="secondary-content" href="#!"><i class="material-icons">clear</i></a></div>
                </li>
                <li class="collection-item">
                  <div class="connected">23:23:34:ff:02:4d<a class="secondary-content" href="#!"><i class="material-icons">clear</i></a></div>
                </li>
                <li class="collection-item">
                  <div class="connected">23:23:34:ff:02:4d<a class="secondary-content" href="#!"><i class="material-icons">clear</i></a></div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content">
              <div class="card-title center">Ban device</div>
              <form method="post">
                <div class="row">
                  <div class="input-field col s12">
                    <input id="mac" type="text" name="mac">
                    <label for="mac">MAC</label>
                  </div>
                  <div class="col s12 center">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <button class="btn waves-effect waves-light" type="submit">Ban</button>
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
    <script src="static/js/main.min.js"></script>
  </body>
</html>
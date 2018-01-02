<?php chdir('..');
require_once 'core/init.php';
$user = new User();
$results = array();
if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

if(Input::exists('get')) {
	$validate = new Validate();
	$validation = $validate->check($_GET, array(
		'ssid' => array(
			'required' => true
		),
		'iface' => array(
			'required' => true
		)));   		
	if(!$validation->passed()) { 
		die("SSID or interface id incorrect");
	}
	
} else {
	die("ssid&iface needed");
}
 ?>
<form method="post" action="../networks.php">
  <div class="row">
    <div class="col s12 center"><?php echo Input::get('ssid'); ?></div>
    <div class="input-field col s12">
      <input id="password" type="password" name="password"/>
      <label for="password">Password</label>
    </div>
    <div class="col s12 center">
      <input type="hidden" name="iface" value="<?php echo Input::get('iface'); ?>"/>
      <input type="hidden" name="ssid" value="<?php echo Input::get('ssid'); ?>"/>
      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
      <button class="btn waves-effect waves-light" type="submit">connect</button>
    </div>
  </div>
</form>
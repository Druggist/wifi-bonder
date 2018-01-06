<?php chdir('..');
require_once 'core/init.php';
$user = new User();
$results = array();
if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

if (Input::exists('get')) {
	$validate = new Validate();
	$validation = $validate->check($_GET, array(
		'iface' => array(
			'required' => true
		)));   		
	if($validation->passed()) { 
		exec('commands/avaible_networks.sh '.$_GET['iface'], $results);
	}
}
 ?>
<h4>Available networks</h4>
<table class="highlight">
  <thead>
    <tr>
      <th>SSID</th>
      <th>Signal</th>
      <th>Security</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody><?php foreach($results as $result) {
	$pieces = explode(':',$result);
	$security = $pieces[2];
	$signal = $pieces[1];
	$ssid = $pieces[0];
	echo '<tr><td>'.$ssid.'</td><td>'.$signal.'</td><td>'.$security.'</td>';
	echo '<td><a class="btn modal-trigger" href="#connect" data-iface="'.$_GET['iface'].'" data-ssid="'.$ssid.'">Connect</a></td></tr>';
} ?>
  </tbody>
</table>
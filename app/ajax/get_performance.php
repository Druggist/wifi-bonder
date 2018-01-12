<?php chdir('..');
require_once 'core/init.php';
$user = new User();
if(!$user->isLoggedIn()) {
		Redirect::to('login.php');
}
exec('speedtest-cli --simple', $r, $o);

$db = DB::getInstance();
if ($db->query('SELECT networkgroupid FROM configs WHERE userid='.$user->data()->userid)->error()){
	die("Could not get user config");
}
$networkgroupid = $db->results()[0]->networkgroupid;
if ($networkgroupid != null) {
	if(!$db->insert('performances', array(
		'ping' => floatval(explode(" ", $r[0])[1]),
		'downloadspeed' => floatval(explode(" ", $r[1])[1]),
		'uploadspeed' => floatval(explode(" ", $r[2])[1]),
		'networkgroupid' => intval($networkgroupid)))) {
		echo 'Could not save performance data';
	}
}

	 ?>
<div class="performance col s12 m4"><?php echo $r[0];
 ?>
</div>
<div class="performance col s6 m4"><?php echo $r[1]; ?></div>
<div class="performance col s6 m4"><?php echo $r[2]; ?></div>
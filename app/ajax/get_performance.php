<?php exec('speedtest-cli --simple', $r, $o);
 ?>
<div class="performance col s12 m4"><?php echo $r[0];
 ?>
</div>
<div class="performance col s6 m4"><?php echo $r[1]; ?></div>
<div class="performance col s6 m4"><?php echo $r[2]; ?></div>
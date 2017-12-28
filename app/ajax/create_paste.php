<?php chdir('..');
require_once 'core/init.php';
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}
 ?>
<form method="post" action="/pastes.php">
  <div class="row">
    <div class="input-field col s12">
      <input id="title" type="text" name="title"/>
      <label for="title">Title</label>
    </div>
    <div class="input-field col s12">
      <input id="data" type="text" name="data"/>
      <label for="text">Text</label>
    </div>
    <div class="col s12">
      <input id="flag" type="checkbox" name="flag"/>
      <label for="flag">Extend expiration date?</label>
    </div>
    <div class="col s12 center">
      <button class="btn waves-effect waves-light" type="submit">Create</button>
    </div>
  </div>
</form>
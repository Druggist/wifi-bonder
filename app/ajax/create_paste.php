<?php chdir('..');
require_once 'core/init.php';
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login.php');
}

If(Input::exists()){	  
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'title' => array(
			'required' => true, 
			'min' => 2,
			'max' => 100
		),
		'data' => array( 
			'required' => true,
			'min' => 0,
			'max' => 1000
		)
	));   		
	if($validation->passed()) {						
		$data = Input::get('data');				
		$title = Input::get('title');			
		$flag = Input::get('flag');
		if($flag!='') {
			$flag='1';
		} else {
			$flag='0';
		}
		$userid = 3;//$user->data->userid;
		if(!$db->insert('pastes', array(
			'data' => $data,
			'title' => $title,
			'flag' => $flag,
			'userid' => 3
			))) {
			die('Wystąpił problem z zapisywaniem danych!');
		}		
	} else {
		foreach($validation->errors() as $error) {
			array_push($messages, $error);
		}
	}
}

 ?>
<form method="post" action="pastes.php">
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
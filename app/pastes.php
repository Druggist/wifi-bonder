<?php require_once 'core/init.php';

$messages = array();
$db = DB::getInstance();

If(Input::exists()){	
	if(Token::check(Input::get('token'))) {     
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'title' => array(
				'required' => true, 
				'min' => 2,
				'max' => 100
			),
			'data' => array( 
				'required' => true,
				'min' => 2,
				'max' => 1000
			)
		));   		
		if($validation->passed()) {						
			$data = Input::get('data');				
			$title = Input::get('title');			
			$flag = Input::get('flag');
			if($flag!='') {
				$flag='E';
			} else {
				$flag='N';
			}
			$userid = 9;//$user->data->userid;
			$date = date('Y-m-d H:i:s');
			try {
				if($db->insert('pastes', array(
					'data' => $data,
					'title' => $title,
					'flag' => $flag,
					'creationtime' => $date,
					'userid' => $userid
				))) {
					Session::flash('success', 'Paste created!');
					Redirect::to('pastes.php');
				} else {
					array_push($messages, "Cannot add paste!");
				}                     
			} catch(Exception $e) {
				die($e->getMessage());
			}		
		} else {
			foreach($validation->errors() as $error) {
				array_push($messages, $error);
			}
		}
	}
}
 
if($db->query("SELECT * FROM pastes ORDER BY `creationtime` DESC")->error()){
	die('Error occured. Try again!');
}
$results = $db->results(); ?><!DOCTYPE html>
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
        <div class="col s12 m6 offset-m3">
          <div class="card">
            <div class="card-content center">
              <div class="card-title">PASTY</div>
            </div>
          </div>
          <div class="card">
            <div class="card-content center"><a class="btn waves-effect waves-light modal-trigger" href="#create_paste">Create paste</a></div>
          </div>
        </div>
      </div>
      <div class="row" id="pastes"><?php foreach($results as $result) {
	echo '<div class="col s12 m4 l3"><a class="modal-trigger" href="#show_paste" data-id="'.$result->pasteid.'"> <div class="card"><div class="card-content"><div class="card-title">'.$result->title.'</div><p class="truncate">'.$result->data.'</p></div></div></a></div>';}
 ?>
      </div>
    </div>
    <div class="modal" id="show_paste">
      <div class="modal-content" id="paste_content">
        <h4>TEMP</h4>
        <p>TEMP</p>
      </div>
      <div class="modal-footer"><a class="modal-action modal-close waves-effect waves-light btn-flat" href="#!">Close			</a></div>
    </div>
    <div class="modal" id="create_paste">
      <div class="modal-content">
        <h4>Create paste</h4>
        <form method="post">
          <div class="row">
            <div class="input-field col s12">
              <input id="title" type="text" name="title">
              <label for="title">Title</label>
            </div>
            <div class="input-field col s12">
              <textarea class="materialize-textarea" id="data"></textarea>
              <label for="data">Content</label>
            </div>
            <div class="col s12">
              <input id="flag" type="checkbox" name="flag">
              <label for="flag">Extended time</label>
            </div>
            <div class="col s12 center">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
              <button class="btn waves-effect waves-light" type="submit">Add</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer"><a class="modal-action modal-close waves-effect waves-light btn-flat" href="#!">Close		</a></div>
    </div>
    <script src="components/jquery/dist/jquery.min.js"></script>
    <script src="components/materialize/dist/js/materialize.min.js"></script>
    <script src="static/js/pastes.min.js"></script><?php if(!empty($messages)){
	echo '<script type="text/javascript">';
	foreach ($messages as $message) {
		echo "Materialize.toast('{$message}', 4000);";
	}
	echo '</script>';
}

				 ?>
  </body>
</html>
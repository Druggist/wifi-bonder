<?php
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null;

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()) {
		foreach ($items as $item => $rules) {
			$item = escape($item);
			foreach ($rules as $rule => $rule_value) {	
				$value = trim($source[$item]);
				if ($rule === 'required' && empty($value)) {
					$this->addError("'{$item}' is required!");
				} else if(!empty($value)){
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value) {
								$this->addError("\"{$item}\" must have at least {$rule_value} signs!");
							}
							break;
						case 'max':
							if (strlen($value) > $rule_value) {
								$this->addError("\"{$item}\" cannot exceed {$rule_value} signs!");
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value]) {
								$this->addError("\"{$item}\" does not match \"{$rule_value}\"!");
							}
							break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if ($check ->count()) {
								$this->addError("\"{$item}\" already exists!");
							}
							break;
						case 'type':
							switch($rule_value) {
								case 'email':
									$pattern = '/^([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([\w\-]+\.)+)([a-zA-Z]{2,4}))$/';
									if (!preg_match($pattern, $value)) {
										$this->addError("Wrong email!");
									}
									break;
								case 'www':
									$pattern = '/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:\/~\+#]*[\w\-\@?^=%&amp;\/~\+#])?/';
									if (!preg_match($pattern, $value)) {
										$this->addError("Wrong url!");
									}
									break;
								case 'number':
									$pattern = '/^[0-9 +,\/-]+$/';
									if (!preg_match($pattern, $value)) {
										$this->addError("Wrong number in {$item}!");
									}
									break;
							}
							break;
					}
				}
			}
		}
		if (empty($this->_errors)) {
			$this->_passed = true;
		}
		return $this;
	}

	private function addError($error) {
		$this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
}
?>
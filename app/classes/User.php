<?php
class User {
	private $_db,
			$_data,
			$_group,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn = false;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		if (!$user) {
			if (Session::exists('user')) {
				$user = Session::get($this->_sessionName);
				if ($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					$this->logout();
				}
			}
		} else {
			$this->find($user);
		}
	}

	public function update($fields = array(), $id = null){
		if (!$id && $this->isLoggedIn()) {
			$id = $this->data()->userid;
		}
		if (!$this->_db->update('users', $id, "userid", $fields)) {
			throw new Exception('There was a problem updating!');
		}
	}

	public function create($fields = array()) {
		if (!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating an account');
		}
	}

	public function find($user = null) {
		if ($user) {
			$field = (is_numeric($user)) ? 'userid' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));
			if ($data && $data->count() > 0) {
				$this->_data = $data->first();
				$group = $this->_db->get('groups', array('groupid', '=', $this->data()->groupid));
				if ($group && $group->count() > 0) {
					$this->_group = $group->first();
				}
				return true;
			}
		}
		return false;
	}

	public function login($username = null, $password = null, $remember = false) {
		if (!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->userid);
		} else { 
			$user = $this->find($username);
			if ($user) {
				if ($this->data()->passwordhash === Hash::make($password, $this->data()->passwordsalt)) {
					Session::put($this->_sessionName, $this->data()->userid);
					if ($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('sessions', array('userid', '=', $this->data()->userid));
						if (!$hashCheck->count()) {
							$this->_db->insert('sessions', array(
								'userid' => $this->data()->userid,
								'hash' => $hash
								));
						} else {
							$hash = $hashCheck->first()->hash;
						}
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					return true;
				}
			}
		}
		return false;
	}

	public function hasPermission($key) {
		$group = $this->_db->get('groups', array('groupid', '=', $this->data()->groupid));
		if ($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);
			if ($permissions[$key] === true) {
				return true;
			}
		}
		return false;
	}
	
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	public function logout() {
		$this->_db->delete('sessions', array('userid', '=', $this->data()->userid));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function data() {
		return $this->_data;
	}

	public function group_data() {
		return $this->_group;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
}
?>
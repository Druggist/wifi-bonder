<?php
class Hash {
	public static function make($string, $salt = '') {
		return hash('sha256', $string.hex2bin($salt));
	}

	public static function salt($length) {
		return bin2hex(random_bytes($length));
	}
	
	public static function string($length) {
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	public static function unique() {
		return self::make(uniqid());
	}
}
?>
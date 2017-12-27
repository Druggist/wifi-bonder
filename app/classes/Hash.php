<?php
class Hash {
	public static function make($string, $salt = '') {
		return hash('sha256', $string.$salt);
	}

	public static function salt($length) {
		return mcrypt_create_iv($length, MCRYPT_DEV_RANDOM);
	}
	
	public static function string($length) {
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	public static function unique() {
		return self::make(uniqid());
	}
}
?>
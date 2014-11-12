<?php
class User extends ActiveRecord\Model {
	static $before_create = array('before_create');

	public function before_create() {
		$this->salt = md5(json_encode($this->attributes()) + Date("Y-m-d H:d:i"));
	}

	public function set_password($password) {
		$this->assign_attribute("password", $this->encode_password($password));
	}

	public function encode_password($password) {
		return md5($password + $this->salt);
	}

}
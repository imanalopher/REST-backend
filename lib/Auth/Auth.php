<?php
//
// There4 Authentication
// =============================================================================
//
// This is a sample class, used in the SlimPHP Unit Testing example
//
// * Author: [Craig Davis](craig.davis@learningstation.com)
// * Since: 10/3/2013
//
// -----------------------------------------------------------------------------
namespace Auth;

class Auth {
	private $user = false;
	private $app = false;
	public function __construct($app) {
		$this->app = $app;
	}

	public function login($usernameOrEmail, $password) {
		$user = \User::find_by_username_or_email($usernameOrEmail, $usernameOrEmail);
		if ($user->password == $user->encode_password($password)) {
			$user->auth_token = md5(json_encode($user->attributes()) + Date("Y-m-d H:m:i"));
			$user->save();
			$this->user = $user;
			return true;
		} else {
			return false;
		}
	}

	public static function format_user($user) {
	    return $user? $user->get_values_for(array("username", "email", "auth_token")) : false;
	}

	public function current_session() {
		if ($this->user) {
			return self::format_user($this->user);
		} else {
			$user = $this->app->request->headers->get("Authorization");
			if ($user == null) {
				return false;
			}
			$matches = array();
			preg_match('/Token token="(.*)", user_email="(.*)"/', $user, $matches);
			if (isset($matches[1]) && isset($matches[2])) {
				$this->user = \User::find_by_auth_token_and_email($matches[1], $matches[2]);
				return self::format_user($this->user);
			} else {
				return false;
			}
		}
	}

}

/* End of file authentication.php */
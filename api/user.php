<?php

namespace Api {

	use Lib;

	class User {

		// Properties
		public $name;
		public $sess_key;
		public $privileges;
		
		public function __construct($name, $sess_key, $privileges) {
			$this->name = $name;
			$this->sess_key = $sess_key;
			$this->privileges = $privileges;
		}
		
		// Login
		public static function login($vars, $obj) {
		
			$retVal = false;
			$user = isset($obj->user_name) ? $obj->user_name : null;
			$pass = isset($obj->password) ? $obj->password : null;

			if (null != $user && null != $pass) {
				$result = Lib\Db::Query('SELECT user_name, user_privileges, user_pass FROM users WHERE user_name = :user', array(':user' => $user));
				if ($result) {
					echo 'Result';
					$row = Lib\Db::Fetch($result);
					if ($row) {
						$hasher = new Lib\PasswordHash(8, false);
						if ($hasher->CheckPassword($pass, $row->user_pass)) {
							unset($row->user_pass);
							$retVal = $row;
						}
					}
				}
			}
			
			return $retVal;
		
		}
		
		// Checks the session cookie against the database and populates stuff if necessary
		public static function getUserFromSession() {
			
			$retVal = false;
			$sess_key = isset($_COOKIE['sess_key']) ? $_COOKIE['sess_key'] : null;
			if ($sess_key) {
				db_Connect();
				$row = db_Fetch(db_Query('SELECT * FROM users WHERE user_sess="' . $sess_key . '"'));
				if ($row) {
					setcookie('sess_key', $row->user_sess, time() + 3600, '/admin/');
					$retVal = self::_populateUser($row);
				}
			}
			
			return $retVal;
			
		}
		
		// Populates user properties
		private static function _populateUser($user) {
			return new User($user->user_name, $user->user_sess, $user->user_privileges);
		}

	}

}
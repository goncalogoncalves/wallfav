<?php

class LoginModel {
	/**
	 * Constructor, expects a Database connection
	 * @param Database $db The Database object
	 */
	public function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('Database connection could not be established.');
		}
	}

	public function registerNewUser($parametros) {

		$user_email = $parametros['user_email'];
		$user_password = $parametros['user_password'];
		$user_password2 = $parametros['user_password2'];

		if (
			filter_var($user_email, FILTER_VALIDATE_EMAIL) AND
			$user_password == $user_password2 AND
			strlen($user_password) >= 4
		) {

			$query = $this->db->prepare("SELECT * FROM users WHERE USER_EMAIL = :USER_EMAIL");
			$query->bindParam(':USER_EMAIL', $user_email, PDO::PARAM_STR);
			$query->execute();

			$encontrou_user = $query->rowCount();
			if ($encontrou_user == 1) {

				return false;
			}

			$hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
			$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));

			$user_activation_hash = sha1(uniqid(mt_rand(), true));
			$user_creation_timestamp = date("YmdHisu") . rand(0, 9999);
			$data_criacao = date("Y:m:d H:i:s");

			$user_http_user_agent = $_SERVER["HTTP_USER_AGENT"];
			$user_http_referer = $_SERVER["HTTP_REFERER"];
			$user_ip = $_SERVER['REMOTE_ADDR'];

			$details = json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json"));
			if (isset($details)) {
				if (isset($details->hostname)) {$user_hostname = $details->hostname;}
				if (isset($details->city)) {$user_city = $details->city;}
				if (isset($details->region)) {$user_region = $details->region;}
				if (isset($details->country)) {$user_country = $details->country;}
				if (isset($details->loc)) {$user_loc = $details->loc;}
				if (isset($details->org)) {$user_org = $details->org;}

			}

			if (!isset($user_http_user_agent)) {$user_http_user_agent = "0";}
			if (!isset($user_http_referer)) {$user_http_referer = "0";}
			if (!isset($user_ip)) {$user_ip = "0";}
			if (!isset($user_hostname)) {$user_hostname = "0";}
			if (!isset($user_city)) {$user_city = "0";}
			if (!isset($user_region)) {$user_region = "0";}
			if (!isset($user_country)) {$user_country = "0";}
			if (!isset($user_loc)) {$user_loc = "0";}
			if (!isset($user_org)) {$user_org = "0";}

			$sql = "INSERT INTO users
        (USER_PASSWORD_HASH, USER_EMAIL, USER_ACTIVE, USER_CREATION_TIMESTAMP, USER_ACTIVATION_HASH, USER_PROVIDER_TYPE, DATA_CRIACAO, HTTP_USER_AGENT, HTTP_REFERER, IP, USER_HOSTNAME, USER_COUNTRY, USER_CITY, USER_REGION, USER_LOC, USER_ORG)
        VALUES
        (:USER_PASSWORD_HASH, :USER_EMAIL, :USER_ACTIVE, :USER_CREATION_TIMESTAMP, :USER_ACTIVATION_HASH, :USER_PROVIDER_TYPE, :DATA_CRIACAO, :HTTP_USER_AGENT, :HTTP_REFERER, :IP, :USER_HOSTNAME, :USER_COUNTRY, :USER_CITY, :USER_REGION, :USER_LOC, :USER_ORG)";
			$query = $this->db->prepare($sql);
			$user_active = false;
			$user_provider_type = 'DEFAULT';
			$query->bindParam(':USER_PASSWORD_HASH', $user_password_hash, PDO::PARAM_STR);
			$query->bindParam(':USER_EMAIL', $user_email, PDO::PARAM_STR);
			$query->bindParam(':USER_ACTIVE', $user_active, PDO::PARAM_BOOL);
			$query->bindParam(':USER_CREATION_TIMESTAMP', $user_creation_timestamp, PDO::PARAM_STR);
			$query->bindParam(':USER_ACTIVATION_HASH', $user_activation_hash, PDO::PARAM_STR);
			$query->bindParam(':DATA_CRIACAO', $data_criacao, PDO::PARAM_STR);
			$query->bindParam(':HTTP_USER_AGENT', $user_http_user_agent, PDO::PARAM_STR);
			$query->bindParam(':HTTP_REFERER', $user_http_referer, PDO::PARAM_STR);
			$query->bindParam(':IP', $user_ip, PDO::PARAM_STR);
			$query->bindParam(':USER_HOSTNAME', $user_hostname, PDO::PARAM_STR);
			$query->bindParam(':USER_COUNTRY', $user_country, PDO::PARAM_STR);
			$query->bindParam(':USER_CITY', $user_city, PDO::PARAM_STR);
			$query->bindParam(':USER_REGION', $user_region, PDO::PARAM_STR);
			$query->bindParam(':USER_LOC', $user_loc, PDO::PARAM_STR);
			$query->bindParam(':USER_ORG', $user_org, PDO::PARAM_STR);
			$query->bindParam(':USER_PROVIDER_TYPE', $user_provider_type, PDO::PARAM_STR);
			$query->execute();

			$count = $query->rowCount();
			if ($count != 1) {

				return false;
			}

			$query = $this->db->prepare("SELECT USER_ID FROM users WHERE USER_EMAIL = :USER_EMAIL");
			$query->bindParam(':USER_EMAIL', $user_email, PDO::PARAM_STR);
			$query->execute();
			if ($query->rowCount() != 1) {
				return false;
			}
			$result_user_row = $query->fetch();
			$user_id = $result_user_row->USER_ID;

			// envia email de confirmacao
			if ($this->sendVerificationEmail($user_nome, $user_email, $user_activation_hash)) {
				return true;
			} else {
				// elimina user se nao conseguiu enviar email
				$query = $this->db->prepare("DELETE FROM users WHERE USER_ID = :last_inserted_id");
				$query->bindParam(':last_inserted_id', $user_id, PDO::PARAM_INT);
				$query->execute();
				return false;
			}

			return true;

		} else {
			return false;
		}

	}

	public function verifyUser($parametros) {
		$email_user = $parametros['e'];
		$hash_user = $parametros['h'];

		$query = $this->db->prepare("SELECT * FROM users WHERE USER_EMAIL = :USER_EMAIL AND USER_ACTIVE = 0");
		$query->bindParam(':USER_EMAIL', $email_user, PDO::PARAM_STR);
		$query->execute();
		$encontrou_user = $query->rowCount();
		if ($encontrou_user != 1) {
			return false;
		}

		$query = $this->db->prepare("UPDATE users
		SET USER_ACTIVE = 1, USER_ACTIVATION_HASH = NULL
		WHERE USER_EMAIL = :USER_EMAIL AND USER_ACTIVATION_HASH = :USER_ACTIVATION_HASH");
		$query->bindParam(':USER_EMAIL', $email_user, PDO::PARAM_STR);
		$query->bindParam(':USER_ACTIVATION_HASH', $hash_user, PDO::PARAM_STR);
		$query->execute();

		if ($query->rowCount() == 1) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Login process (for DEFAULT user accounts).
	 * Users who login with Facebook etc. are handled with loginWithFacebook()
	 * @return bool success state
	 */
	public function login($parametros) {

		$user_email = isset($parametros['user_email']) ? $parametros['user_email'] : "";
		$user_password = isset($parametros['user_password']) ? $parametros['user_password'] : "";
		$remember_me = isset($parametros['remember_me']) ? $parametros['remember_me'] : "";

		$user_email = "user@domain.dev";

		$sth = $this->db->prepare("SELECT USER_ID,
    		USER_NAME,
    		USER_EMAIL,
    		USER_PASSWORD_HASH,
    		USER_ACTIVE,
    		USER_ACCOUNT_TYPE,
    		USER_FAILED_LOGINS,
    		USER_LAST_LOGIN_TIMESTAMP
    		FROM   users
    		WHERE  USER_EMAIL = :USER_EMAIL AND USER_PROVIDER_TYPE = :provider_type");

		$provider_type = 'DEFAULT';

		// DEFAULT is the marker for "normal" accounts (that have a password etc.)
		// There are other types of accounts that don't have passwords etc. (FACEBOOK)
		$sth->bindParam(':USER_EMAIL', $user_email, PDO::PARAM_STR);
		$sth->bindParam(':provider_type', $provider_type, PDO::PARAM_STR);
		$sth->execute();

		$count = $sth->rowCount();

		// fetch one row (we only have one result)
		$result = $sth->fetch();

		$user_email = $result->USER_EMAIL;

		// login process, write the user data into session
		//session_start();
		$_SESSION['su_id'] = $result->USER_ID;
		$_SESSION['su_email'] = $user_email;
		$_SESSION['su_type'] = 'DEFAULT';

		// reset the failed login counter for that user (if necessary)
		if ($result->USER_LAST_LOGIN_TIMESTAMP > 0) {
			$sql = "UPDATE users SET USER_FAILED_LOGINS = 0, USER_LAST_LOGIN_TIMESTAMP = NULL
    			WHERE USER_ID = :USER_ID AND USER_FAILED_LOGINS != 0";
			$sth = $this->db->prepare($sql);
			$sth->bindParam(':USER_ID', $result->USER_ID, PDO::PARAM_INT);
			$sth->execute();

		}

		// generate integer-timestamp for saving of last-login date
		$user_last_login_timestamp = time();
		// write timestamp of this login into database (we only write "real" logins via login form into the
		// database, not the session-login on every page request
		$sql = "UPDATE users SET user_last_login_timestamp = :user_last_login_timestamp WHERE USER_ID = :USER_ID";
		$sth = $this->db->prepare($sql);
		$sth->bindParam(':USER_ID', $result->USER_ID, PDO::PARAM_INT);
		$sth->bindParam(':user_last_login_timestamp', $user_last_login_timestamp, PDO::PARAM_STR);
		$sth->execute();

		// if user has checked the "remember me" checkbox, then write cookie
		if ($remember_me == "1") {

			// generate 64 char random string
			$random_token_string = hash('sha256', mt_rand());

			// write that token into database
			$sql = "UPDATE users SET USER_REMEMBERME_TOKEN = :USER_REMEMBERME_TOKEN WHERE USER_ID = :USER_ID";
			$sth = $this->db->prepare($sql);
			$sth->bindParam(':USER_REMEMBERME_TOKEN', $random_token_string, PDO::PARAM_STR);
			$sth->bindParam(':USER_ID', $result->USER_ID, PDO::PARAM_INT);
			$sth->execute();

			// generate cookie string that consists of user id, random string and combined hash of both
			$cookie_string_first_part = $result->USER_ID . ':' . $random_token_string;
			$cookie_string_hash = hash('sha256', $cookie_string_first_part);
			$cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

			// set cookie
			setcookie('rememberme', $cookie_string, time() + COOKIE_RUNTIME, "/", COOKIE_DOMAIN);
		}

		// login was successful
		return true;

	}

	/**
	 * performs the login via cookie (for DEFAULT user account, FACEBOOK-accounts are handled differently)
	 * @return bool success state
	 */
	public function loginWithCookie() {
		$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';

		// do we have a cookie var ?
		if (!$cookie) {
			return false;
		}

		// check cookie's contents, check if cookie contents belong together
		list($user_id, $token, $hash) = explode(':', $cookie);
		if ($hash !== hash('sha256', $user_id . ':' . $token)) {

			return false;
		}

		// do not log in when token is empty
		if (empty($token)) {

			return false;
		}

		$provider_type = 'DEFAULT';

		// get real token from database (and all other data)
		$query = $this->db->prepare("SELECT USER_ID, USER_NAME, USER_EMAIL, USER_PASSWORD_HASH, USER_ACTIVE,
    		USER_ACCOUNT_TYPE, USER_FAILED_LOGINS, USER_LAST_LOGIN_TIMESTAMP
    		FROM users
    		WHERE USER_ID = :USER_ID
    		AND USER_REMEMBERME_TOKEN = :USER_REMEMBERME_TOKEN
    		AND USER_REMEMBERME_TOKEN IS NOT NULL
    		AND USER_PROVIDER_TYPE = :provider_type");

		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':USER_REMEMBERME_TOKEN', $token, PDO::PARAM_STR);
		$query->bindParam(':provider_type', $provider_type, PDO::PARAM_STR);
		$query->execute();
		//$query->execute(array(':USER_ID' => $user_id, ':USER_REMEMBERME_TOKEN' => $token, ':provider_type' => 'DEFAULT'));
		$count = $query->rowCount();
		if ($count == 1) {
			// fetch one row (we only have one result)
			$result = $query->fetch();

			$_SESSION['su_id'] = $result->USER_ID;
			$_SESSION['su_email'] = $result->USER_EMAIL;
			$_SESSION['su_type'] = 'DEFAULT';

			// generate integer-timestamp for saving of last-login date
			$user_last_login_timestamp = time();
			// write timestamp of this login into database (we only write "real" logins via login form into the
			// database, not the session-login on every page request
			$sql = "UPDATE users SET user_last_login_timestamp = :user_last_login_timestamp WHERE USER_ID = :USER_ID";
			$sth = $this->db->prepare($sql);
			$sth->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
			$sth->bindParam(':user_last_login_timestamp', $user_last_login_timestamp, PDO::PARAM_STR);
			$sth->execute();

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Log out process, deletes cookie, deletes session
	 */
	public function logout() {

		setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);

		// delete the session
		$_SESSION = array();
		session_destroy();
	}

	/**
	 * Deletes the (invalid) remember-cookie to prevent infinitive login loops
	 */
	public function deleteCookie() {

		setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
	}

	/**
	 * sends an email to the provided email address
	 * @param int $user_id user's id
	 * @param string $user_email user's email
	 * @param string $user_activation_hash user's mail verification hash string
	 * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
	 */
	private function sendVerificationEmail($user_nome, $user_email, $user_activation_hash) {

		$email_de_nome = APLICACAO_NOME;
		$email_de_email = APLICACAO_EMAIL;
		$email_para_nome = $user_nome;
		$email_para_email = $user_email;
		$email_assunto = "Email confirmation";

		$email_mensagem = '<a href="' . URL . '" target="_blank" title="wallfav"><img src="' . APLICACAO_IMAGEM . '" height="58" width="229"></a><br><br><br>';
		$email_mensagem .= "Thank you for your registration.<br>";
		$email_mensagem .= "To activate your account, please click in the link below:<br><br>";
		$email_mensagem .= "<a title='Activate account' href='http://wallfav.com/verify?e=" . $user_email . "&h=" . $user_activation_hash . "' target='_blank'>Activate account!</a>";

		$email = new email();
		$enviou_email = $email->sendEmail($email_de_nome, $email_de_email, $email_para_nome, $email_para_email, $email_assunto, $email_mensagem);

		if ($enviou_email == true) {
			return true;
		} else {
			return false;
		}

	}

	public function passwordReset($parametros) {
		$user_email = $parametros['user_email'];
		$user_email = strip_tags($user_email);

		$user_hash = sha1(uniqid(mt_rand(), true));
		$hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
		$user_password_reset_hash = password_hash($user_hash, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
		$temporary_timestamp = time();

		$provider_type = 'DEFAULT';

		$query = $this->db->prepare("SELECT USER_ID FROM users WHERE USER_EMAIL = :USER_EMAIL LIMIT 1 ");
		$query->bindParam(':USER_EMAIL', $user_email, PDO::PARAM_STR);
		$query->execute();
		//$erro = $query->errorInfo();
		$count = $query->rowCount();
		if ($count != 1) {return false;}

		$query_two = $this->db->prepare("UPDATE users SET
      		USER_PASSWORD_RESET_HASH = :USER_PASSWORD_RESET_HASH,
      		USER_PASSWORD_HASH = :USER_PASSWORD_HASH,
      		USER_PASSWORD_RESET_TIMESTAMP = :USER_PASSWORD_RESET_TIMESTAMP
      		WHERE
      		USER_EMAIL = :USER_EMAIL AND
      		USER_PROVIDER_TYPE = :provider_type");
		$query_two->bindParam(':USER_PASSWORD_HASH', $user_password_reset_hash, PDO::PARAM_STR);
		$query_two->bindParam(':USER_PASSWORD_RESET_HASH', $user_password_reset_hash, PDO::PARAM_STR);
		$query_two->bindParam(':USER_PASSWORD_RESET_TIMESTAMP', $temporary_timestamp, PDO::PARAM_STR);
		$query_two->bindParam(':USER_EMAIL', $user_email, PDO::PARAM_STR);
		$query_two->bindParam(':provider_type', $provider_type, PDO::PARAM_STR);
		$query_two->execute();
		$count = $query_two->rowCount();
		if ($count != 1) {
			return false;
		}

		$email_de_nome = APLICACAO_NOME;
		$email_de_email = APLICACAO_EMAIL;
		$email_para_nome = $user_email;
		$email_para_email = $user_email;
		$email_assunto = "New Password";

		$email_mensagem = '<a href="' . URL . '" target="_blank" title="wallfav"><img src="' . APLICACAO_IMAGEM . '" height="58" width="229"></a><br><br><br>';
		$email_mensagem .= "Your new password is:<b><br>" . $user_hash . "</b><br><br>";
		$email_mensagem .= "Don't forget that you can change this password in <a href='" . URL . "settings'>settings page</a><br>";

		$email = new email();
		$enviou_email = $email->sendEmail($email_de_nome, $email_de_email, $email_para_nome, $email_para_email, $email_assunto, $email_mensagem);

		if ($enviou_email == true) {
			return true;
		} else {
			return false;
		}

	}

	public function loginWithFacebook($parametros) {

		$fb_user_email = $parametros['fb_user_email'];
		$fb_user_first_name = $parametros['fb_user_first_name'];
		$fb_user_gender = $parametros['fb_user_gender'];
		$fb_user_id = $parametros['fb_user_id'];
		$fb_user_last_name = $parametros['fb_user_last_name'];
		$fb_user_link = $parametros['fb_user_link'];
		$fb_user_locale = $parametros['fb_user_locale'];
		$fb_user_name = $parametros['fb_user_name'];
		$fb_user_timezone = $parametros['fb_user_timezone'];
		$fb_user_updated_time = $parametros['fb_user_updated_time'];
		$fb_user_verified = $parametros['fb_user_verified'];

		$query = $this->db->prepare("SELECT USER_ID,
        	USER_NAME,
        	USER_EMAIL,
        	USER_ACCOUNT_TYPE,
        	USER_PROVIDER_TYPE
        	FROM users
        	WHERE USER_FACEBOOK_UID = :USER_FACEBOOK_UID ");
		$query->bindParam(':USER_FACEBOOK_UID', $fb_user_id, PDO::PARAM_STR);
		$query->execute();
		//$query->execute(array(':USER_FACEBOOK_UID' => $fb_user_id ));
		$count = $query->rowCount();
		if ($count != 1) {
			return false;
		}
		$result = $query->fetch();

	}

	public function getFacebookLoginUrl() {

		$facebook = new Facebook(array('appId' => FACEBOOK_LOGIN_APP_ID, 'secret' => FACEBOOK_LOGIN_APP_SECRET));

		$facebook_login_url = $facebook->getLoginUrl(array('redirect_uri' => URL . FACEBOOK_LOGIN_PATH));

		return $facebook_login_url;
	}

	public function getFacebookRegisterUrl() {

		$facebook = new Facebook(array('appId' => FACEBOOK_LOGIN_APP_ID, 'secret' => FACEBOOK_LOGIN_APP_SECRET));

		$redirect_url_after_facebook_auth = URL . FACEBOOK_REGISTER_PATH;

		$facebook_register_url = $facebook->getLoginUrl(array(
			'scope' => 'email',
			'redirect_uri' => $redirect_url_after_facebook_auth,
		));

		return $facebook_register_url;
	}

	public function registerWithFacebook() {

		$facebook = new Facebook(array('appId' => FACEBOOK_LOGIN_APP_ID, 'secret' => FACEBOOK_LOGIN_APP_SECRET));

		$user = $facebook->getUser();

		if ($user) {
			try {

				$facebook_user_data = $facebook->api('/me');
			} catch (FacebookApiException $e) {

				error_log($e);
				$user = null;
				$_SESSION["feedback_negative"][] = FEEDBACK_FACEBOOK_OFFLINE;
				return false;
			}
		}

		if (!$facebook_user_data) {
			$_SESSION["feedback_negative"][] = FEEDBACK_FACEBOOK_UID_ALREADY_EXISTS;
			return false;
		}

		if (!$this->facebookUserHasEmail($facebook_user_data)) {
			$_SESSION["feedback_negative"][] = FEEDBACK_FACEBOOK_EMAIL_NEEDED;
			return false;
		}

		if ($this->facebookUserIdExistsAlreadyInDatabase($facebook_user_data)) {
			$_SESSION["feedback_negative"][] = FEEDBACK_FACEBOOK_UID_ALREADY_EXISTS;
			return false;
		}

		if ($this->facebookUserNameExistsAlreadyInDatabase($facebook_user_data)) {
			$facebook_user_data["username"] = $this->generateUniqueUserNameFromExistingUserName($facebook_user_data["username"]);
			if ($this->facebookUserNameExistsAlreadyInDatabase($facebook_user_data)) {
				//shouldn't get here if we managed to generate a unique name!
				$_SESSION["feedback_negative"][] = FEEDBACK_FACEBOOK_USERNAME_ALREADY_EXISTS;
				return false;
			}
		}

		if ($this->facebookUserEmailExistsAlreadyInDatabase($facebook_user_data)) {
			$_SESSION["feedback_negative"][] = FEEDBACK_FACEBOOK_EMAIL_ALREADY_EXISTS;
			return false;
		}

		if ($this->registerNewUserWithFacebook($facebook_user_data)) {
			$_SESSION["feedback_positive"][] = FEEDBACK_FACEBOOK_REGISTER_SUCCESSFUL;
			return true;
		} else {
			$_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
			return false;
		}

		// default return
		return false;
	}

	public function registerNewUserWithFacebook($facebook_user_data) {
		// delete dots from facebook-username (it's the common way to do this like that)
		$clean_USER_NAME_from_facebook = str_replace(".", "", $facebook_user_data["username"]);
		// generate integer-timestamp for saving of account-creating date
		$user_creation_timestamp = time();

		$sql = "INSERT INTO users (USER_NAME, USER_EMAIL, USER_CREATION_TIMESTAMP, USER_ACTIVE, USER_PROVIDER_TYPE, USER_FACEBOOK_UID)
    	VALUES (:USER_NAME, :USER_EMAIL, :USER_CREATION_TIMESTAMP, :USER_ACTIVE, :USER_PROVIDER_TYPE, :USER_FACEBOOK_UID)";
		$query = $this->db->prepare($sql);
		$query->execute(array(':USER_NAME' => $clean_USER_NAME_from_facebook,
			':USER_EMAIL' => $facebook_user_data["email"],
			':USER_CREATION_TIMESTAMP' => $user_creation_timestamp,
			':USER_ACTIVE' => 1,
			':USER_PROVIDER_TYPE' => 'FACEBOOK',
			':USER_FACEBOOK_UID' => $facebook_user_data["id"]));

		$count = $query->rowCount();
		if ($count == 1) {
			$query = $this->db->prepare("SELECT USER_ID, USER_NAME, USER_EMAIL, USER_ACCOUNT_TYPE, USER_PROVIDER_TYPE
    			FROM   users
    			WHERE  USER_NAME = :USER_NAME AND USER_PROVIDER_TYPE = :provider_type");
			$query->execute(array(':USER_NAME' => $clean_USER_NAME_from_facebook, ':provider_type' => 'FACEBOOK'));
			$count_from_select_statement = $query->rowCount();
			if ($count_from_select_statement == 1) {
				// registration successful
				return true;
			}
		}
		// default return
		return false;
	}

	public function facebookUserHasEmail($facebook_user_data) {
		if (isset($facebook_user_data["email"]) && !empty($facebook_user_data["email"])) {
			return true;
		}
		// default return
		return false;
	}

	public function facebookUserIdExistsAlreadyInDatabase($facebook_user_data) {
		$query = $this->db->prepare("SELECT USER_ID FROM users WHERE USER_FACEBOOK_UID = :USER_FACEBOOK_UID");
		$query->execute(array(':USER_FACEBOOK_UID' => $facebook_user_data["id"]));

		if ($query->rowCount() == 1) {
			return true;
		}
		// default return
		return false;
	}

	public function facebookUserNameExistsAlreadyInDatabase($facebook_user_data) {
		// delete dots from facebook's username (it's the common way to do this like that)
		$clean_USER_NAME_from_facebook = str_replace(".", "", $facebook_user_data["username"]);

		$query = $this->db->prepare("SELECT USER_ID FROM users WHERE USER_NAME = :clean_USER_NAME_from_facebook");
		$query->execute(array(':clean_USER_NAME_from_facebook' => $clean_USER_NAME_from_facebook));

		if ($query->rowCount() == 1) {
			return true;
		}
		// default return
		return false;
	}

	public function facebookUserEmailExistsAlreadyInDatabase($facebook_user_data) {
		$query = $this->db->prepare("SELECT USER_ID FROM users WHERE USER_EMAIL = :facebook_email");
		$query->execute(array(':facebook_email' => $facebook_user_data["email"]));

		if ($query->rowCount() == 1) {
			return true;
		}
		// default return
		return false;
	}

}

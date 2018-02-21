<?php

ini_set('session.gc_maxlifetime', 86400);

session_start();

define('URL', 'http://wallfav_opensource.dev/');
define('BASE_URL', 'http://wallfav_opensource.dev/');
define('RELATIVE_URL', $_SERVER['DOCUMENT_ROOT'] . "/");

define('UPLOADS_DIRECTORY', 'http://www.wallfav.dev/uploads/');
define('UPLOADS_DIRECTORY_LOCAL', $_SERVER['DOCUMENT_ROOT'] . "/uploads/");

define('APLICACAO_NOME_SIMPLES', 'wallfav');
define('APLICACAO_NOME', 'wallfav');
define('APLICACAO_EMAIL', '');
define('APLICACAO_IMAGEM', 'http://wallfav_opensource.dev/img/logo_wallfav.png');

// db config
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'wallfav_opensource');
define('DB_USER', 'root');
define('DB_PASS', '');

// buscar configs à bd
$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);

// configs gerais
try {
	$query = $db->prepare("SELECT * FROM configs WHERE id = 1");
	$query->execute();
	$configs = $query->fetch();
	$config_website_titulo = $configs->WEBSITE_TITULO;
	$config_website_descricao = $configs->WEBSITE_DESCRICAO;
	$config_website_keywords = $configs->WEBSITE_KEYWORDS;
} catch (Exception $e) {
	//echo $e;
	$config_website_titulo = "WallFav - Put your favourite websites on a wall!";
	$config_website_descricao = "Put your favourite websites on a wall!";
	$config_website_keywords = "favourites,manager,organize";
}

// rotas
try {
	$query = $db->prepare("SELECT VALOR, LIGACAO FROM routes WHERE VALIDA = 1");
	$query->execute();
	$rotas = $query->fetchAll();

	$array_rotas = array();

	foreach ($rotas as $key => $value) {
		$array_rotas[$value->VALOR] = $value->LIGACAO;
	}

} catch (Exception $e) {
	$array_rotas = '';
}

// fechar a conexao à bd
$db = null;

// variaveis globais mas que podem ser alteradas in runtime
$array_global_configs = array(
	'website_titulo' => $config_website_titulo,
	'website_descricao' => $config_website_descricao,
	'website_keywords' => $config_website_keywords,
	'website_rotas' => $array_rotas,
	'website_nome' => APLICACAO_NOME_SIMPLES,
);

// the hash cost factor, PHP's internal default is 10.
define("HASH_COST_FACTOR", "10");

define("FEEDBACK_UNKNOWN_ERROR", "Error unknown.");
define("FEEDBACK_EMAIL_ALREADY_REGISTERED", "That email is already registered.");
define("FEEDBACK_INVALID_FORM", "The form is invalid.");
define("FEEDBACK_ACCOUNT_CREATION_FAILED", "Was not possible to create the account. Please try again.");

$_SESSION["feedback_negative"] = '';

// 1209600 seconds = 2 weeks
define('COOKIE_RUNTIME', 1209600);
// the domain where the cookie is valid for, for local development ".127.0.0.1" and ".localhost" will work
// IMPORTANT: always put a dot in front of the domain, like ".mydomain.com" !
define('COOKIE_DOMAIN', '.wallfav.dev');

// Options: 0 = off, 1 = commands, 2 = commands and data, perfect to see SMTP errors, see the PHPMailer manual for more
define("PHPMAILER_DEBUG_MODE", 2);
// use SMTP or basic mail() ? SMTP is strongly recommended
define("EMAIL_USE_SMTP", true);
// name of your host
define("EMAIL_SMTP_HOST", '');
// leave this true until your SMTP can be used without login
define("EMAIL_SMTP_AUTH", true);
// SMTP provider username
define("EMAIL_SMTP_USERNAME", '');
// SMTP provider password
define("EMAIL_SMTP_PASSWORD", '');
// SMTP provider port
define("EMAIL_SMTP_PORT", 25);
// SMTP encryption, usually SMTP providers use "tls" or "ssl", for details see the PHPMailer manual
define("EMAIL_SMTP_ENCRYPTION", '');

define("EMAIL_VERIFICATION_URL", URL . "login/verify");
define("EMAIL_VERIFICATION_FROM_EMAIL", "no-reply@example.com");
define("EMAIL_VERIFICATION_FROM_NAME", "Wallfav");
define("EMAIL_VERIFICATION_SUBJECT", "Account activation - Wallfav");
define("EMAIL_VERIFICATION_CONTENT", "Please activate your account: ");

?>

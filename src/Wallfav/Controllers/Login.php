<?php

namespace Wallfav\Controllers;

use CodigoUtil\captcha as captcha;
use Controller;
use Slim\Slim;

class Login extends \SlimController\SlimController {

	/**
	 * Index, default action (shows the login form), when you do login/index
	 */
	function indexAction() {

		// se ja estiver logado faz redirect para a home
		$app = Slim::getInstance();

		if (!isset($_SESSION['su_email'])) {
			$this->render('login/index', array('layout' => "0"));
		} else {
			$app->redirect('dashboard');
		}

	}

	/**
	 * The login action, when you do login/login
	 */
	function loginAction() {
		$app = Slim::getInstance();

		$controller = new Controller();
		$login_model = $controller->loadModel('LoginModel');
		$login_successful = $login_model->login($app->request->params());
		if ($login_successful == true) {
			$login_successful = "1";
		} else {
			$login_successful = "0";
		}

		echo $login_successful;
	}

	function registerAction() {

		if (isset($_SESSION['su_email'])) {
			$this->redirect('/login');
		} else {
			//$this->render('login/register');
			$teste = "valor teste";

			$this->render('login/register', array('layout' => "0", "teste" => $teste));

		}
	}

	// TODO: nao esta acabado
	function sendVerificationEmailAction() {
		$app = Slim::getInstance();

		$controller = new Controller();
		$login_model = $controller->loadModel('LoginModel');
		$user_verificado = $login_model->sendVerificationEmail($app->request->params());

		if ($user_verificado == true) {
			$app->redirect('login/accountActivated');
		} else {
			$app->redirect('login/accountNotActivated');
		}
	}

	function verifyAction() {
		$app = Slim::getInstance();

		$controller = new Controller();
		$login_model = $controller->loadModel('LoginModel');
		$user_verificado = $login_model->verifyUser($app->request->params());

		if ($user_verificado == true) {
			$app->redirect('login/accountActivated');
		} else {
			$app->redirect('login/accountNotActivated');
		}

	}

	function accountActivatedAction() {
		$this->render('login/accountActivated', array('layout' => "0"));
	}

	function accountNotActivatedAction() {
		$this->render('login/accountNotActivated', array('layout' => "0"));
	}

	function addCaptchaAction() {

		$app = Slim::getInstance();
		//if ($app->request->isAjax()) {
		$captcha = new captcha("fonts/");
		$html_captcha = $captcha->criar_captcha();

		echo $html_captcha;
		//}
	}

	function validateCaptchaAction() {

		$app = Slim::getInstance();
		if ($app->request->isAjax()) {
			//$app->request->params()
			if (isset($_GET['input_captcha'])) {$input_captcha = $_GET['input_captcha'];} else { $input_captcha = "0";}
			if ($input_captcha != "0") {
				$captcha = new captcha();
				$captcha_valido = $captcha->validar_captcha($input_captcha);
				echo $captcha_valido;
			}
		}
	}

	public function registerUserAction() {
		$app = Slim::getInstance();

		$controller = new Controller();
		$login_model = $controller->loadModel('LoginModel');
		$registo_com_sucesso = $login_model->registerNewUser($app->request->params());
		if ($registo_com_sucesso == true) {
			$registo_com_sucesso = "1";
		} else {
			$registo_com_sucesso = "0";
		}

		echo $registo_com_sucesso;
	}

	public function loginWithFacebookAction() {
		$app = Slim::getInstance();

		$controller = new Controller();
		$login_model = $controller->loadModel('LoginModel');
		$login_facebook = $login_model->loginWithFacebook($app->request->params());
		if ($login_facebook == true) {
			$login_facebook = "1";
		} else {
			$login_facebook = "0";
		}

		echo $login_facebook;
	}

	function logoutAction() {
		$controller = new Controller();

		$login_model = $controller->loadModel('LoginModel');
		$login_model->logout();

		$this->redirect('/');
	}

	function requestPasswordResetAction() {
		$this->render('login/requestPasswordReset', array('layout' => "0"));
	}

	function passwordResetAction() {
		$app = Slim::getInstance();

		$controller = new Controller();
		$login_model = $controller->loadModel('LoginModel');
		$resultado = $login_model->passwordReset($app->request->params());

		echo $resultado;
	}

}

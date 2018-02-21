<?php

namespace Wallfav\Controllers;

use Controller;
use Slim\Slim;

class Home extends \SlimController\SlimController {

	public function __constructor() {

	}

	public function indexAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | put your favourite websites on a wall!";

		$app = Slim::getInstance();
		$nome_app = $app->getName();
		$parametros = $app->request->params();
		if (isset($parametros['o'])) {
			$origem = $parametros['o'];
		} else {
			$origem = '';
		}

		$variavel = 'Home:index';

		if ($origem == "s") {
			$this->render('home/index', array(
				'layout' => "0",
				'variavel' => $variavel,
				'nome_app' => $nome_app,
			));
		} else {
			if (!isset($_SESSION['su_email'])) {
				$this->render('home/index', array(
					'layout' => "0",
					'variavel' => $variavel,
					'nome_app' => $nome_app,
				));
			} else {
				$app->redirect('dashboard');
			}
		}

	}

	public function coffeeAction() {
		$app = Slim::getInstance();

		$this->render('home/coffee', array('layout' => "0"));
	}

	public function termsAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | terms & conditions";

		$app = Slim::getInstance();

		$this->render('home/terms', array('layout' => "0"));
	}

	public function privacyAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | privacy policy";

		$app = Slim::getInstance();

		$this->render('home/privacy', array('layout' => "0"));
	}

	public function sendFeedbackAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | send feedback";

		$app = Slim::getInstance();

		$this->render('home/send_feedback', array('layout' => "0"));
	}

	public function contactsAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | contacts";

		$app = Slim::getInstance();

		$this->render('home/contacts', array('layout' => "0"));
	}

	public function deleteAccountAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | delete account";

		$app = Slim::getInstance();

		$this->render('home/delete_account', array('layout' => "0"));
	}

	public function deleteAccountNowAction() {

		$app = Slim::getInstance();

		$controller = new Controller();
		$home_model = $controller->loadModel('HomeModel');
		$delete_successful = $home_model->deleteAccountNow($app->request->params());
		if ($delete_successful == true) {
			$delete_successful = "1";
		} else {
			$delete_successful = "0";
		}

		echo $delete_successful;
	}

	public function errorAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav";

		$mensagem_erro = "Ups. Error!";
		$link_valor = "/";
		$link_texto = "Inicio";

		$this->render('home/error', array(
			'mensagem_erro' => $mensagem_erro,
			'link_valor' => $link_valor,
			'link_texto' => $link_texto,
		));
	}

	public function notFoundAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | not found";

		$titulo = "Not Found. 404.";
		$mensagem_not_found = "
    The page you are looking for cannot be found, for real!
    <br><br>
    <span style='font-size:0.8em;'>If you think the page exists but there is something wrong, please <a href='/send-feedback'>send feedback</a></span>.
    ";
		$imagem = "";
		$link_valor = BASE_URL;
		$link_texto = "Inicio";

		$this->render('home/not_found', array(
			'layout' => '0',
			'mensagem_not_found' => $mensagem_not_found,
			'titulo' => $titulo,
			'imagem' => $imagem,
			'link_valor' => $link_valor,
			'link_texto' => $link_texto,
		));
	}

	public function withoutPermissionAction() {
		$mensagem_without_permission = "Ups. Without Permission!";
		$link_valor = "/";
		$link_texto = "Inicio";

		$this->render('home/without_permission', array(
			'mensagem_without_permission' => $mensagem_without_permission,
			'link_valor' => $link_valor,
			'link_texto' => $link_texto,
		));
	}

}

?>

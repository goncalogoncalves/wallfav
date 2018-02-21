<?php

namespace Wallfav\Controllers;

use Controller;
use Slim\Slim;

class Dashboard extends \SlimController\SlimController {

	public function __constructor() {

	}

	public function indexAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | dashboard";

		$app = Slim::getInstance();
		$nome_app = $app->getName();

		$controller = new Controller();
		$dashboard_model = $controller->loadModel('DashboardModel');
		$resultado = $dashboard_model->getUserInfo();
		$user_active = $resultado["user_active"];

		if ($user_active == "0") {
			$app->redirect('login/accountNotActivated');
		}

		if (!isset($_SESSION['su_email'])) {
			$this->render('login/index');
		} else {
			$this->render('dashboard/dashboard');
		}
	}

	public function settingsAction() {
		global $array_global_configs;
		$array_global_configs['website_titulo'] = "wallfav | settings";

		$app = Slim::getInstance();

		if (!isset($_SESSION['su_email'])) {
			$this->render('login/index');
		} else {
			$this->render('dashboard/settings', array('layout' => "0"));
		}

	}

	public function exportInfoAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->exportInfo($app->request->params());
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}

		$resultado = json_encode($resultado);

		echo $resultado;
	}

	public function addWebsiteAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->addWebsite($app->request->params());
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}

		$resultado = json_encode($resultado);

		echo $resultado;

	}

	public function addCategoryAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {

			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$add_category_successful = $dashboard_model->addCategory($app->request->params());
			if ($add_category_successful == false) {
				$add_category_successful = "0";
			}

		} else {
			$add_category_successful = "0";
		}

		echo $add_category_successful;

	}

	public function saveWebsitePositionAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->saveWebsitePosition($app->request->params());
			if ($resultado == false) {
				$resultado = "0";
			}
		} else {
			$resultado = "0";
		}

		echo $resultado;
	}

	public function saveLayoutAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->saveLayout($app->request->params());
			if ($resultado == false) {
				$resultado = "0";
			}
		} else {
			$resultado = "0";
		}

		echo $resultado;
	}

	public function loadCategoriasAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->loadCategorias();
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}
		echo $resultado;
	}

	public function loadInfoCategoryAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->loadInfoCategory($app->request->params());
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}
		echo $resultado;
	}

	public function saveInfoCategoryAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->saveInfoCategory($app->request->params());
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}
		echo $resultado;
	}

	public function loadCategoryContentAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->loadCategoryContent($app->request->params());
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}

		echo $resultado;
	}

	public function loadWebsitesDesorganizadosAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->loadWebsitesDesorganizados();
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}
		echo $resultado;
	}

	public function loadDestaquesAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->loadDestaques();
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}
		echo $resultado;
	}

	public function deleteWebsiteAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->deleteWebsite($app->request->params());
			if ($resultado == false) {
				$resultado = "0";
			} else {
				$resultado = "1";
			}
		} else {
			$resultado = "0";
		}

		echo $resultado;

	}

	public function deleteCategoryAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {
			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->deleteCategory($app->request->params());
			if ($resultado == false) {
				$resultado = "0";
			} else {
				$resultado = "1";
			}
		} else {
			$resultado = "0";
		}

		echo $resultado;

	}

	public function sendFeedbackAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {

			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$feedback_successful = $dashboard_model->sendFeedback($app->request->params());
			if ($feedback_successful == true) {
				$feedback_successful = "1";
			} else {
				$feedback_successful = "0";
			}

		} else {
			$feedback_successful = "0";
		}

		echo $feedback_successful;

	}

	public function changePasswordAction() {

		$app = Slim::getInstance();

		if ($app->request->isAjax()) {

			$controller = new Controller();
			$dashboard_model = $controller->loadModel('DashboardModel');
			$resultado = $dashboard_model->changePassword($app->request->params());
			if ($resultado == false) {$resultado = "0";}
		} else {
			$resultado = "0";
		}
		echo $resultado;

	}

}

?>

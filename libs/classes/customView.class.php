<?php


use Slim\Slim;

class customView extends \Slim\View
{
    /**
     * Data available to the view templates
     * @var \Slim\Helper\Set
     */
    protected $data;


    public function __construct()
    {
        $this->data = new \Slim\Helper\Set();
    }

    public function render($filename, $data = null )
    {

        $templatePathname = $this->getTemplatePathname($filename);
        if (!is_file($templatePathname)) {
            $app->log->error("View cannot render $filename because the template does not exist");
            $app->redirect('/error');
            //$app->render('500.php');
            //throw new \RuntimeException("View cannot render `$filename` because the template does not exist");
        }

        $data = array_merge($this->data->all(), (array) $data);
        extract($data);

        // verifica se o user nao estiver logado se tem cookie para logar
        if (!isset($_SESSION['su_email'])) {
            $controller = new Controller();
            $login_model = $controller->loadModel('LoginModel');
            $login_successful = $login_model->loginWithCookie();
        }

        ob_start();

        if (isset($data["layout"])) {
            if ($data["layout"] == "0") {
                require $templatePathname;
            }
        }else{
            require APP_PATH . '/src/Wallfav/Views/layouts/top.php';
            require $templatePathname;
            require APP_PATH . '/src/Wallfav/Views/layouts/bottom.php';
        }

        return ob_get_clean();
    }


    /**
     * renders the feedback messages into the view
     */
    /*public function renderFeedbackMessages()
    {
        // echo out the feedback messages (errors and success messages etc.),
        // they are in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
        require VIEWS_PATH . '_templates/feedback.php';

        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }*/

    /**
     * Checks if the passed string is the currently active controller.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller
     * @return bool Shows if the controller is used or not
     */
    private function checkForActiveController($filename, $navigation_controller)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];

        if ($active_controller == $navigation_controller) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * Checks if the passed string is the currently active controller-action (=method).
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_action
     * @return bool Shows if the action/method is used or not
     */
    private function checkForActiveAction($filename, $navigation_action)
    {
        $split_filename = explode("/", $filename);
        $active_action = $split_filename[1];

        if ($active_action == $navigation_action) {
            return true;
        }
        // default return of not true
        return false;
    }

    /**
     * Checks if the passed string is the currently active controller and controller-action.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller_and_action
     * @return bool
     */
    private function checkForActiveControllerAndAction($filename, $navigation_controller_and_action)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];
        $active_action = $split_filename[1];

        $split_filename = explode("/", $navigation_controller_and_action);
        $navigation_controller = $split_filename[0];
        $navigation_action = $split_filename[1];

        if ($active_controller == $navigation_controller AND $active_action == $navigation_action) {
            return true;
        }
        // default return of not true
        return false;
    }
}

<?php

class Application
{

    /** @var null The controller */
    private $controllerName = null;
    private $controller = null;

    /** @var null The method (of the above controller), often also named "action" */
    private $actionName = null;

    /** @var array URL parameters */
    private $params = array();

    public function requireAllLanguage()
    {
        $languageFolder = LANGUAGE_PATH;
        if (is_dir($languageFolder)) {
            if ($dh = opendir($languageFolder)) {
                while (($languageFile = readdir($dh)) !== false) {
                    if (!empty($languageFile) && (strpos(strtolower($languageFile), "_" . LANGUAGE . ".php"))) {
                        require LANGUAGE_PATH . $languageFile;
                    }
                }
                closedir($dh);
            }
        }
    }

    public function requireAllContextPath()
    {
        $contextPathFolder = CONTEXT_PATH;
        if (is_dir($contextPathFolder)) {
            if ($dh = opendir($contextPathFolder)) {
                while (($contextPathFile = readdir($dh)) !== false) {
                    if (!empty($contextPathFile) && (strpos(strtolower($contextPathFile), "_path.php"))) {
                        require CONTEXT_PATH . $contextPathFile;
                    }
                }
                closedir($dh);
            }
        }
    }

    public function requireAllRender()
    {
        $renderFolder = RENDER_PATH;
        if (is_dir($renderFolder)) {
            if ($dh = opendir($renderFolder)) {
                while (($renderFile = readdir($dh)) !== false) {
                    if (!empty($renderFile) && (strpos(strtolower($renderFile), "_render.php"))) {
                        require RENDER_PATH . $renderFile;
                    }
                }
                closedir($dh);
            }
        }
    }

    /**
     * "Start" the application:
     * Analyze the URL elements and calls the according controller/method or the fallback
     */
    public function __construct()
    {
        $this->requireAllLanguage();
        $this->requireAllContextPath();
        $this->requireAllRender();
        // create array with URL parts in $url
        $this->splitUrl();

        // check for controller: no controller given ? then load start-page
        if (!$this->controllerName) {
            require CONTROLLER_PATH . 'home_ctrl.php';
            $page = new HomeCtrl();
            $page->index();
        } elseif ($this->controllerName == 'public') {
            return;
        } elseif (!file_exists(CONTROLLER_PATH . $this->controllerName . '_ctrl.php')) {
            header('location: ' . URL);
            return;
            require CONTROLLER_PATH . 'error_ctrl.php';
            $page = new ErrorCtrl();
            $page->notFound();
        } else {
            // here we did check for controller: does such a controller exist ?
            // if so, then load this file and create this controller
            // example: if controller would be "car", then this line would translate into: $this->car = new car();            

            $controllerFile = $this->controllerName . '_ctrl.php';
            $controllerName = $this->controllerName . 'Ctrl';
            require CONTROLLER_PATH . $controllerFile;
            $this->controller = new $controllerName();

            // check for method: does such a method exist in the controller ?
            if (!method_exists($this->controller, $this->actionName)) {
                if (strlen($this->actionName) == 0) {
                    // no action defined: call the default index() method of a selected controller
                    $this->controller->index();
                } else {
                    header('location: ' . URL);
                    return;
                    require CONTROLLER_PATH . 'error_ctrl.php';
                    $page = new ErrorCtrl();
                    $page->notFound();
                }
            } else {
                $reflectionMethod = new \ReflectionMethod($this->controller, $this->actionName);
                $numberOfRequiredParameters = $reflectionMethod->getNumberOfRequiredParameters();

                if (sizeof($this->params) < $numberOfRequiredParameters) {

                    header('location: ' . URL);
                    return;
                    require CONTROLLER_PATH . 'error_ctrl.php';
                    $page = new ErrorCtrl();
                    $page->notFound();
                } else {
                    if (!empty($this->params)) {
                        // Call the method and pass arguments to it
                        call_user_func_array(array($this->controller, $this->actionName), $this->params);
                    } else {
                        // If no parameters are given, just call the method without parameters, like $this->home->method();
                        $this->controller->{$this->actionName}();
                    }
                }
            }
        }
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // split URL
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Put URL parts into according properties
            // By the way, the syntax here is just a short form of if/else, called "Ternary Operators"
            // @see http://davidwalsh.name/php-shorthand-if-else-ternary-operators
            $this->controllerName = isset($url[0]) ? $url[0] : null;
            $this->actionName = isset($url[1]) ? $url[1] : null;

            // Remove controller and action from the split URL
            unset($url[0], $url[1]);

            // Rebase array keys and store the URL params
            $this->params = array_values($url);

            // for debugging. uncomment this if you have problems with the URL
//            echo 'Controller: ' . $this->url_controller . '<br>';
//            echo 'Action: ' . $this->url_action . '<br>';
//            echo 'Parameters: ' . print_r($this->url_params, true) . '<br>';
        }
    }
}

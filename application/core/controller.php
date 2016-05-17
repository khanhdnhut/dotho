<?php
/*
 * This is the "Base controller class". All the other "real" controllers extend this class 
 * 
 */

class Controller
{
    /* @var null Database Connection */

    public $db = null;

    /* @var null View Object */
    public $view = null;

    /**
     * Whenever a controller is created, we also:
     * 1. Create a database connection (that will be passed to all models that need a database connection)
     * 2. Create a view object
     */
    function __construct()
    {
        Session::init();
        // Create database connection
        try {
            $this->db = new Database();
        } catch (PDOException $e) {
            die('Database connection could not be established');
        }

        // Create a view object
        $this->view = new View();
    }

    /**
     * Load the model with the given name.
     * Note that the model class name is written in "LoginModel", the model's filename is the same in lowercase letters
     * @param string $name The name of the model
     * @return object model
     */
    public function loadModel($name)
    {
        $modelName = $name . 'Model';
        if (class_exists($modelName, FALSE)) {
            return new $modelName($this->db);
        } else {
            $path = MODEL_PATH . strtolower($name) . '_model.php';
            // Check for model: Does such a model exist?
            if (file_exists($path)) {
                require MODEL_PATH . strtolower($name) . '_model.php';
                // Return new model and pass the database connection to the model
                return new $modelName($this->db);
            } else {
                return null;
            }
        }
    }

    public function autoloadModel($name)
    {
        $modelName = $name . 'Model';
        if (!class_exists($modelName, FALSE)) {
            $path = MODEL_PATH . strtolower($name) . '_model.php';
            // Check for model: Does such a model exist?
            if (file_exists($path)) {
                require MODEL_PATH . strtolower($name) . '_model.php';
            }
        }
    }
    
    public static function autoloadController($name)
    {
        $ctrlName = $name . 'Ctrl';
        if (!class_exists($ctrlName, FALSE)) {
            $path = CONTROLLER_PATH . strtolower($name) . '_ctrl.php';
            // Check for model: Does such a model exist?
            if (file_exists($path)) {
                require CONTROLLER_PATH . strtolower($name) . '_ctrl.php';
            }
        }
    }

    public function getBO($name)
    {
        $boName = $name . 'BO';
        if (class_exists($boName, FALSE)) {
            return new $boName($this->db);
        } else {
            $path = BO_PATH . strtolower($name) . '_bo.php';
            // Check for model: Does such a model exist?
            if (file_exists($path)) {
                require BO_PATH . strtolower($name) . '_bo.php';
                // Return new model and pass the database connection to the model
                return new $boName();
            } else {
                return null;
            }
        }
    }

    public function autoloadBO($name)
    {
        $boName = $name . 'BO';
        if (!class_exists($boName, FALSE)) {
            $path = BO_PATH . strtolower($name) . '_bo.php';
            // Check for model: Does such a model exist?
            if (file_exists($path)) {
                require BO_PATH . strtolower($name) . '_bo.php';
            }
        }
    }
}

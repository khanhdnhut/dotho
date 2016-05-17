<?php

/*
 * This is the "Base model class". All the other "real" models extend this class 
 * 
 */

class Model {
    
    /* @var null Database Connection */
    public $db = null;
    
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Load the model with the given name.
     * Note that the model class name is written in "LoginModel", the model's filename is the same in lowercase letters
     * @param string $name The name of the model
     * @return object model
     */
    public function loadModel ($name) {   
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
    
    public static function autoloadModel($name)
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
    
    public function autoloadBO($name){
        $boName = $name . 'BO';
        if (!class_exists($boName, FALSE)){
            $path = BO_PATH . strtolower($name) . '_bo.php';
            // Check for model: Does such a model exist?
            if (file_exists($path)) {
                require BO_PATH . strtolower($name) . '_bo.php';            
            }
        }
    }
        
    public function getBO ($name) {   
        $boName = $name . 'BO';
        if (class_exists($boName, FALSE)) {
            return new $boName();
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
}

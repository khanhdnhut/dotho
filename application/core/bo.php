<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BO {
    function __construct()
    {
        
    }
    
    public function getBO ($name) {   
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
    public static function autoloadBO($name)
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
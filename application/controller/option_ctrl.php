<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class OptionCtrl extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index() {
        if(Auth::handleLogin()){
            $this->view->renderAdmin('option/option_index');
        }
    }
    
    public function loadOption(){
        Model::autoloadModel('Option');
        $model = new OptionModel($this->db);
        $model->loadOption();
    }
}

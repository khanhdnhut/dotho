<?php

/**
 * Class Error
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class ErrorCtrl extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct() {
        parent::__construct();
    }
    /**
     * PAGE: index
     * This method handles the error page that will be shown when a page is not found
     */
    public function index()
    {
        // load views
        $this->view->render(ERROR_RV_INDEX);
    }    
    
    public function notFound()
    {
        // load views
        $this->view->render(ERROR_RV_NOTFOUND);
    }
}

<?php

/*
 * 
 * 
 */
//function autoload($class){
//    // if file does not exist in LIBS_PATH folder
//    if (file_exists(CORE_PATH . $class . ".php")) {
//        require CORE_PATH . $class . ".php";
//    } else {
//        exit ('The file ' . $class . '.php is missing in the libs folder.');
//    }
//}
//
//// spl_autoload_register defines the function that is called every time a file is missing.
//// as wee created thi function above, every time a file is needed, autoload(the_need_class) is called.
//spl_autoload_register("autoload");


require CORE_PATH . "application.php";
require CORE_PATH . "auth.php";
require CORE_PATH . "controller.php";
require CORE_PATH . "database.php";
require CORE_PATH . "menu.php";
require CORE_PATH . "model.php";
require CORE_PATH . "session.php";
require CORE_PATH . "view.php";
require CORE_PATH . "bo.php";
require CORE_PATH . "utils.php";
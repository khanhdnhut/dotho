<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
define('ENVIRONMENT', 'development');

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

/**
 * Configuration for: URL
 * Here we auto-detect your applications URL and the potential sub-folder. Works perfectly on most servers and in local
 * development environments (like WAMP, MAMP, etc.). Don't touch this unless you know what you do.
 *
 * URL_PUBLIC_FOLDER:
 * The folder that is visible to public, users will only have access to that folder so nobody can have a look into
 * "/application" or other folder inside your application or call any other .php file than index.php inside "/public".
 *
 * URL_PROTOCOL:
 * The protocol. Don't change unless you know exactly what you do.
 *
 * URL_DOMAIN:
 * The domain. Don't change unless you know exactly what you do.
 *
 * URL_SUB_FOLDER:
 * The sub-folder. Leave it like it is, even if you don't use a sub-folder (then this will be just "/").
 *
 * URL:
 * The final, auto-detected URL (build via the segments above). If you don't want to use auto-detection,
 * then replace this line with full URL (and sub-folder) and a trailing slash.
 */

define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', 'http://');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER . '/');

/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 */
define('DB_TYPE', 'mysql');
//define('DB_HOST', 'mysql.hostinger.vn');
define('DB_HOST', '127.0.0.1');
//define('DB_NAME', 'u658911921_dotho');
define('DB_NAME', 'tours');
//define('DB_USER', 'u658911921_dotho');
define('DB_USER', 'root');
//define('DB_PASS', '123456a@');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');

/*
 * Configuration for: Application folders Path
 * 
 */
define('CONFIG_PATH', APP. 'config' . DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH', APP. 'controller' . DIRECTORY_SEPARATOR);
define('LANGUAGE_PATH', APP. 'language' . DIRECTORY_SEPARATOR);
define('CONTEXT_PATH', APP. 'path' . DIRECTORY_SEPARATOR);
define('RENDER_PATH', APP. 'render' . DIRECTORY_SEPARATOR);
define('CORE_PATH', APP. 'core' . DIRECTORY_SEPARATOR);
define('LIB_PATH', APP. 'lib' . DIRECTORY_SEPARATOR);
define('MODEL_PATH', APP. 'model' . DIRECTORY_SEPARATOR);
define('BO_PATH', APP. 'bo' . DIRECTORY_SEPARATOR);
define('VIEW_PATH', APP. 'view' . DIRECTORY_SEPARATOR);

/*
 * Configuration for: Application folders Path
 * 
 */

define('VIEW_TEMPLATES_PATH', VIEW_PATH. '_templates/');
define('VIEW_MODULE_PATH', VIEW_PATH. 'module/');


/*
 * Configuration for: Public URL
 * 
 */

define('PUBLIC_REL', 'public/');
define('PUBLIC_PATH', 'public' . DIRECTORY_SEPARATOR);
define('PUBLIC_URL', URL. 'public/');
define('PUBLIC_CSS', PUBLIC_URL. 'css/');
define('PUBLIC_IMG', PUBLIC_URL. 'img/');
define('PUBLIC_JS', PUBLIC_URL. 'js/');
define('PUBLIC_REF', PUBLIC_URL. 'ref/');
define('PUBLIC_UPLOAD', PUBLIC_URL. 'uploads/');
define('PUBLIC_UPLOAD_REL', PUBLIC_REL . 'uploads/');
define('PUBLIC_UPLOAD_PATH', PUBLIC_PATH. 'uploads' . DIRECTORY_SEPARATOR);



<?php

/*
 * Class Database
 * Creates a PDO database connection. This connection will be passed into the models.
 * So we use the same connection for all models andd prevent to open multiple connections at once
 */

class Database extends PDO {
    public function __construct() {
        // Set the options of the PDO connection.
        // In this case, we set the fetche mode to "objects", which means all
        // results will be objects, like this: $result->user_name
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, 
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        
        // Generate a database connection, using the PDO connector
        parent::__construct(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', 
                DB_USER, DB_PASS, $options);
    }
}

?>

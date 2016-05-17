<?php

class OptionModel extends Model {

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(\Database $db) {
        parent::__construct($db);
    }

    public function loadOption() {
        $sth = $this->db->prepare("SELECT * FROM " . TABLE_OPTIONS);

        $sth->execute();
        $count = $sth->rowCount();
        if ($count > 0) {
            $result = $sth->fetchAll();
            $_SESSION['options'] = new stdClass();
            foreach ($result as $option) {
                $optionName = $option->option_name;
                $_SESSION['options']->$optionName = $option->option_value;
            }
        } else {
            $_SESSION['options'] = new stdClass();
        }
    }

}

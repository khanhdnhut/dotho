<?php
    if (count($module) > 0) {
        echo "<div style='width:25.6%' class='ja-col column ja-inset1' id='ja-inset1'>";
        for($i = 0; $i < count($module); $i++){
            require VIEW_MODULE_PATH . $module[$i] . '.php';
        }
        echo "</div>";
    }
?>

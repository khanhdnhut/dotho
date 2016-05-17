


<li id="menu-settings" class="wp-has-submenu <?php
if ($this->checkForActiveController($_GET['url'], 'category')) {
    echo "wp-has-current-submenu wp-menu-open";
} else {
    echo "wp-not-current-submenu";
}

?> menu-top menu-icon-page menu-top-last">
    <a aria-haspopup="true" class="wp-has-submenu <?php
    if ($this->checkForActiveController($_GET['url'], 'category')) {
        echo "wp-has-current-submenu wp-menu-open";
    } else {
        echo "wp-not-current-submenu";
    }

    ?> menu-top menu-icon-page menu-top-last" href="<?php echo URL . CATEGORY_CP_INDEX; ?>">
        <div class="wp-menu-arrow">
            <div></div>
        </div>
        <div class="wp-menu-image dashicons-before dashicons-admin-page">
            <br>
        </div>
        <div class="wp-menu-name"><?php echo CATEGORY_TITLE_CATEGORIES; ?></div>
    </a>
    <ul class="wp-submenu wp-submenu-wrap" style="">
        <li aria-hidden="true" class="wp-submenu-head"><?php echo CATEGORY_TITLE_CATEGORIES; ?></li>

        <li class="wp-first-item <?php
        if ($this->checkForActiveControllerAndAction($_GET['url'], CATEGORY_CP_INDEX)) {
            echo "current";
        }

        ?>"><a class="wp-first-item <?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], CATEGORY_CP_INDEX)) {
                echo "current";
            }

            ?>" href="<?php echo URL . CATEGORY_CP_INDEX; ?>"><?php echo CATEGORY_TITLE_ALL_CATEGORY; ?></a></li>                                
        <li class="wp-first-item <?php
        if ($this->checkForActiveControllerAndAction($_GET['url'], CATEGORY_CP_ADD_NEW)) {
            echo "current";
        }

        ?>"><a class="wp-first-item <?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], CATEGORY_CP_ADD_NEW)) {
                echo "current";
            }

            ?>" href="<?php echo URL . CATEGORY_CP_ADD_NEW; ?>"><?php echo CATEGORY_TITLE_ADD_NEW; ?></a></li>   
    </ul>
</li>

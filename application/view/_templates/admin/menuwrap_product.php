


<li id="menu-settings" class="wp-has-submenu <?php
if ($this->checkForActiveController($_GET['url'], 'product')) {
    echo "wp-has-current-submenu wp-menu-open";
} else {
    echo "wp-not-current-submenu";
}

?> menu-top menu-icon-media menu-top-last">
    <a aria-haspopup="true" class="wp-has-submenu <?php
    if ($this->checkForActiveController($_GET['url'], 'product')) {
        echo "wp-has-current-submenu wp-menu-open";
    } else {
        echo "wp-not-current-submenu";
    }

    ?> menu-top menu-icon-media menu-top-last" href="<?php echo URL . PRODUCT_CP_INDEX; ?>">
        <div class="wp-menu-arrow">
            <div></div>
        </div>
        <div class="wp-menu-image dashicons-before dashicons-admin-multisite">
            <br>
        </div>
        <div class="wp-menu-name"><?php echo PRODUCT_TITLE_PRODUCTS; ?></div>
    </a>
    <ul class="wp-submenu wp-submenu-wrap" style="">
        <li aria-hidden="true" class="wp-submenu-head"><?php echo PRODUCT_TITLE_PRODUCTS; ?></li>

        <li class="wp-first-item <?php
        if ($this->checkForActiveControllerAndAction($_GET['url'], PRODUCT_CP_INDEX)) {
            echo "current";
        }

        ?>"><a class="wp-first-item <?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], PRODUCT_CP_INDEX)) {
                echo "current";
            }

            ?>" href="<?php echo URL . PRODUCT_CP_INDEX; ?>"><?php echo PRODUCT_TITLE_ALL_PRODUCT; ?></a></li>                                
        <li class="wp-first-item <?php
        if ($this->checkForActiveControllerAndAction($_GET['url'], PRODUCT_CP_ADD_NEW)) {
            echo "current";
        }

        ?>"><a class="wp-first-item <?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], PRODUCT_CP_ADD_NEW)) {
                echo "current";
            }

            ?>" href="<?php echo URL . PRODUCT_CP_ADD_NEW; ?>"><?php echo PRODUCT_TITLE_ADD_NEW; ?></a></li>   
    </ul>
</li>

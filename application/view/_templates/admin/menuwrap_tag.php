
<li id="menu-settings" class="wp-has-submenu <?php
if ($this->checkForActiveController($_GET['url'], 'tag')) {
    echo "wp-has-current-submenu wp-menu-open";
} else {
    echo "wp-not-current-submenu";
}

?> menu-top menu-icon-appearance menu-top-last">
    <a aria-haspopup="true" class="wp-has-submenu <?php
    if ($this->checkForActiveController($_GET['url'], 'tag')) {
        echo "wp-has-current-submenu wp-menu-open";
    } else {
        echo "wp-not-current-submenu";
    }

    ?> menu-top menu-icon-appearance menu-top-last" href="<?php echo URL . TAG_CP_INDEX; ?>">
        <div class="wp-menu-arrow">
            <div></div>
        </div>
        <div class="wp-menu-image dashicons-before dashicons-admin-appearance">
            <br>
        </div>
        <div class="wp-menu-name"><?php echo TAG_TITLE_TAGS; ?></div>
    </a>
    <ul class="wp-submenu wp-submenu-wrap" style="">
        <li aria-hidden="true" class="wp-submenu-head"><?php echo TAG_TITLE_TAGS; ?></li>

        <li class="wp-first-item <?php
        if ($this->checkForActiveControllerAndAction($_GET['url'], TAG_CP_INDEX)) {
            echo "current";
        }

        ?>"><a class="wp-first-item <?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], TAG_CP_INDEX)) {
                echo "current";
            }

            ?>" href="<?php echo URL . TAG_CP_INDEX; ?>"><?php echo TAG_TITLE_ALL_TAGS; ?></a></li>                                
        <li class="wp-first-item <?php
        if ($this->checkForActiveControllerAndAction($_GET['url'], TAG_CP_ADD_NEW)) {
            echo "current";
        }

        ?>"><a class="wp-first-item <?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], TAG_CP_ADD_NEW)) {
                echo "current";
            }

            ?>" href="<?php echo URL . TAG_CP_ADD_NEW; ?>"><?php echo TAG_TITLE_ADD_NEW; ?></a></li>   
    </ul>
</li>

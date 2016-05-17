

<li id="menu-dashboard" class="wp-first-item wp-has-submenu <?php
if ($this->checkForActiveController($_GET['url'], 'admin')) {
    echo "wp-has-current-submenu wp-menu-open";
} else {
    echo "wp-not-current-submenu";
}

?> menu-top menu-top-first menu-icon-dashboard menu-top-last">
    <a class="wp-first-item wp-has-submenu <?php
    if ($this->checkForActiveController($_GET['url'], 'admin')) {
        echo "wp-has-current-submenu wp-menu-open";
    } else {
        echo "wp-not-current-submenu";
    }

    ?> menu-top menu-top-first menu-icon-dashboard menu-top-last" href="<?php echo URL . ADMIN_CP; ?>" aria-haspopup="false">
        <div class="wp-menu-arrow">
            <div></div>
        </div>
        <div class="wp-menu-image dashicons-before dashicons-dashboard">
            <br>
        </div>
        <div class="wp-menu-name"><?php echo TITLE_DASHBOARD; ?></div>
    </a>
</li>
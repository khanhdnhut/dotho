
<?php
if (in_array(Auth::getCapability(), array(
        USER_VALUE_ADMIN))) {

    ?>                                     
    <li id="menu-users" class="wp-has-submenu <?php
    if ($this->checkForActiveController($_GET['url'], 'user')) {
        echo "wp-has-current-submenu wp-menu-open";
    } else {
        echo "wp-not-current-submenu";
    }

    ?> menu-top menu-icon-users">
        <a aria-haspopup="true" class="wp-has-submenu <?php
        if ($this->checkForActiveController($_GET['url'], 'user')) {
            echo "wp-has-current-submenu wp-menu-open";
        } else {
            echo "wp-not-current-submenu";
        }

        ?> menu-top menu-icon-users" href="<?php echo URL . USER_CP_INDEX; ?>">
            <div class="wp-menu-arrow">
                <div></div>
            </div>
            <div class="wp-menu-image dashicons-before dashicons-admin-users">
                <br>
            </div>
            <div class="wp-menu-name"><?php echo USER_TITLE_USERS; ?></div>
        </a>
        <ul class="wp-submenu wp-submenu-wrap">
            <li aria-hidden="true" class="wp-submenu-head"><?php echo USER_TITLE_USERS; ?></li>
            <li class="wp-first-item <?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], USER_CP_INDEX)) {
                echo "current";
            }

            ?>"><a class="wp-first-item <?php
                if ($this->checkForActiveControllerAndAction($_GET['url'], USER_CP_INDEX)) {
                    echo "current";
                }

                ?>" href="<?php echo URL . USER_CP_INDEX; ?>"><?php echo USER_TITLE_ALL_USERS; ?></a></li>
            <li class="<?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], USER_CP_ADD_NEW)) {
                echo "current";
            }

            ?>"><a class="<?php
                    if ($this->checkForActiveControllerAndAction($_GET['url'], USER_CP_ADD_NEW)) {
                        echo "current";
                    }

                    ?>" href="<?php echo URL . USER_CP_ADD_NEW; ?>"><?php echo TITLE_ADD_NEW; ?></a></li>
            <li class="<?php
            if ($this->checkForActiveControllerAndAction($_GET['url'], USER_CP_PROFILE)) {
                echo "current";
            }

            ?>"><a class="<?php
                    if ($this->checkForActiveControllerAndAction($_GET['url'], USER_CP_PROFILE)) {
                        echo "current";
                    }

                    ?>" href="<?php echo URL . USER_CP_PROFILE; ?>"><?php echo USER_TITLE_YOUR_PROFILE; ?></a></li>
        </ul>
    </li>
    <?php
} else if (in_array(Auth::getCapability(), array(
        USER_VALUE_SUBSCRIBER,
        USER_VALUE_CONTRIBUTOR,
        USER_VALUE_AUTHOR,
        USER_VALUE_EDITOR))) {

    ?>
    <li id="menu-users" class="<?php
    if ($this->checkForActiveController($_GET['url'], 'user')) {
        echo "wp-has-current-submenu wp-menu-open";
    } else {
        echo "wp-not-current-submenu";
    }

    ?> menu-top menu-icon-users menu-top-first">
        <a aria-haspopup="true" class="wp-has-submenu <?php
        if ($this->checkForActiveController($_GET['url'], 'user')) {
            echo "wp-has-current-submenu wp-menu-open";
        } else {
            echo "wp-not-current-submenu";
        }

        ?> menu-top menu-icon-users" href="<?php echo URL . USER_CP_INDEX; ?>">
            <div class="wp-menu-arrow">
                <div></div>
            </div>
            <div class="wp-menu-image dashicons-before dashicons-admin-users">
                <br>
            </div>
            <div class="wp-menu-name"><?php echo USER_TITLE_YOUR_PROFILE; ?></div>
        </a>
    </li>
    <?php
}

?>
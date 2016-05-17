<?php
if (!is_null(Session::get('userInfo'))) {
    $userLoginBO = json_decode(Session::get('userInfo'));

    ?>
    <div id="wpcontent">
        <div class="" id="wpadminbar">
            <div tabindex="0" aria-label="Toolbar" role="navigation" id="wp-toolbar" class="quicklinks">
                <ul class="ab-top-menu" id="wp-admin-bar-root-default">
                    <li id="wp-admin-bar-menu-toggle"><a href="#" class="ab-item" aria-expanded="false"><span class="ab-icon"></span><span class="screen-reader-text">Menu</span></a> </li>                
                    <li class="menupop" id="wp-admin-bar-site-name"><a href="<?php echo URL; ?>" aria-haspopup="true" class="ab-item"><?php echo WEBSITE_NAME; ?></a>
                        <div class="ab-sub-wrapper">
                            <ul class="ab-submenu" id="wp-admin-bar-site-name-default">
                                <li id="wp-admin-bar-view-site"><a href="<?php echo URL; ?>" class="ab-item"><?php echo WEBSITE_NAME; ?></a> </li>
                            </ul>
                        </div>
                    </li>
                    <?php
                    if (!in_array(Auth::getCapability(), array(
                            USER_VALUE_SUBSCRIBER))) {

                        ?>                                     

                        <li class="menupop" id="wp-admin-bar-new-content"><a href="http://localhost:8082/wordpress1/wp-admin/post-new.php" aria-haspopup="true" class="ab-item"><span class="ab-icon"></span><span class="ab-label"><?php echo TITLE_NEW; ?></span></a>
                            <div class="ab-sub-wrapper">
                                <ul class="ab-submenu" id="wp-admin-bar-new-content-default">
                                    <?php
                                    if (in_array(Auth::getCapability(), array(
                                            USER_VALUE_ADMIN))) {

                                        ?>                                     
                                        <li id="wp-admin-bar-new-user"><a href="<?php echo URL . USER_CP_ADD_NEW; ?>" class="ab-item"><?php echo USER_TITLE_USERS; ?></a> </li>
                                        <?php
                                    }
                                    
                                    if (in_array(Auth::getCapability(), array(
                                            USER_VALUE_ADMIN))) {

                                        ?>                                     
                                        <li id="wp-admin-bar-new-category"><a href="<?php echo URL . CATEGORY_CP_ADD_NEW; ?>" class="ab-item"><?php echo CATEGORY_TITLE_CATEGORY_1; ?></a> </li>
                                        <?php
                                    }
                                    
                                    if (in_array(Auth::getCapability(), array(
                                            USER_VALUE_ADMIN))) {

                                        ?>                                     
                                        <li id="wp-admin-bar-new-product"><a href="<?php echo URL . PRODUCT_CP_ADD_NEW; ?>" class="ab-item"><?php echo PRODUCT_TITLE_PRODUCT_1; ?></a> </li>
                                        <?php
                                    }

                                    ?>

                                </ul>
                            </div>
                        </li>
                        <?php
                    }

                    ?>

                </ul>
                <ul class="ab-top-secondary ab-top-menu" id="wp-admin-bar-top-secondary">
                    <li class="menupop with-avatar" id="wp-admin-bar-my-account">
                        <a href="#" aria-haspopup="true" class="ab-item">
                            Hello <?php echo htmlspecialchars($userLoginBO->user_login); ?><img width="26" height="26" class="avatar avatar-26 photo" srcset="<?php
                if (isset($userLoginBO->avatar_url)) {
                    echo URL . htmlspecialchars($userLoginBO->avatar_url);
                } else {
                    echo URL . USER_VALUE_AVATAR_DEFAULT;
                }

                    ?>" src="<?php
                                                                                                if (isset($userLoginBO->avatar_url)) {
                                                                                                    echo URL . htmlspecialchars($userLoginBO->avatar_url);
                                                                                                } else {
                                                                                                    echo URL . USER_VALUE_AVATAR_DEFAULT;
                                                                                                }

                                                                                                ?>" alt="">                       
                        </a>
                        <div class="ab-sub-wrapper">
                            <ul class="ab-submenu" id="wp-admin-bar-user-actions">
                                <li id="wp-admin-bar-user-info">
                                    <a href="<?php echo URL . USER_CP_PROFILE; ?>" tabindex="-1" class="ab-item"><img width="64" height="64" class="avatar avatar-64 photo" srcset="<?php
                                                                                            if (isset($userLoginBO->avatar_url)) {
                                                                                                echo URL . htmlspecialchars($userLoginBO->avatar_url);
                                                                                            } else {
                                                                                                echo URL . USER_VALUE_AVATAR_DEFAULT;
                                                                                            }

                                                                                                ?>" src="<?php
                                                                                                                                if (isset($userLoginBO->avatar_url)) {
                                                                                                                                    echo URL . htmlspecialchars($userLoginBO->avatar_url);
                                                                                                                                } else {
                                                                                                                                    echo URL . USER_VALUE_AVATAR_DEFAULT;
                                                                                                                                }

                                                                                                                                ?>" alt=""><span class="display-name"><?php echo htmlspecialchars($userLoginBO->user_login); ?></span></a>
                                </li>
                                <li id="wp-admin-bar-edit-profile"><a href="<?php echo URL . USER_CP_EDIT . $userLoginBO->user_id . "/" . htmlspecialchars($userLoginBO->user_login); ?>" class="ab-item"><?php echo TITLE_EDIT_MY_PROFILE; ?></a> </li>
                                <li id="wp-admin-bar-logout"><a href="<?php echo URL . USER_CP_LOGOUT; ?>" class="ab-item"><?php echo TITLE_LOGOUT; ?></a> </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <a href="<?php echo URL . USER_CP_LOGOUT; ?>" class="screen-reader-shortcut"><?php echo TITLE_LOGOUT; ?></a>
        </div>
        <div role="main" id="wpbody">
            <div tabindex="0" aria-label="Main content" id="wpbody-content" style="overflow: hidden;">               
                <div class="wrap">
                    <?php
                } else {
                    Auth::handleLogin();
                }

                ?>

<body class="wp-admin wp-core-ui js  index-php auto-fold admin-bar branch-4-4 version-4-4 admin-color-fresh locale-en-ca customize-support svg">
    <script type="text/javascript">
        document.body.className = document.body.className.replace('no-js', 'js');
    </script>

    <div id="wpwrap">

        <div aria-label="Main menu" role="navigation" id="adminmenumain">
            <a class="screen-reader-shortcut" href="#wpbody-content">Skip to main content</a>
            <a class="screen-reader-shortcut" href="#wp-toolbar">Skip to toolbar</a>
            <div id="adminmenuback"></div>
            <div id="adminmenuwrap" style="position: fixed;">
                <ul id="adminmenu">
                    <?php
                    require VIEW_TEMPLATES_PATH . 'admin/menuwrap_admin.php';
                    require VIEW_TEMPLATES_PATH . 'admin/menuwrap_product.php';
                    require VIEW_TEMPLATES_PATH . 'admin/menuwrap_category.php';
                    require VIEW_TEMPLATES_PATH . 'admin/menuwrap_tag.php';
                    
                    require VIEW_TEMPLATES_PATH . 'admin/menuwrap_user.php';
                    ?>    
                    <li class="hide-if-no-js" id="collapse-menu">
                        <div id="collapse-button">
                            <div></div>
                        </div><span><?php echo TITLE_COLLAPSE_MENU; ?></span></li>
                </ul>
            </div>
        </div>
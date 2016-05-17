<!DOCTYPE html>
<html lang="vi">
    <head>
        <?php require VIEW_TEMPLATES_PATH . 'home/metadata.php'; ?>  
        <link href="<?php echo PUBLIC_CSS . "sondong/bootstrap.css" ?>" rel="stylesheet">
        <link href="<?php echo PUBLIC_CSS . "sondong/addstyle.css" ?>" rel="stylesheet">
        <script async="" src="//www.google-analytics.com/analytics.js"></script>
        <script src="<?php echo PUBLIC_JS . "includes/jquery/jquery.js" ?>"></script>
        <script async="async" src="<?php echo PUBLIC_REF . "bootstrap/js/bootstrap.js" ?>"></script>	
        <script>
            jQuery(document).ready(function () {
                function toggleNavbarMethod() {
                    if (jQuery(window).width() > 768) {
                        jQuery('.navbar .dropdown').on('mouseover', function () {
                            jQuery('.dropdown-toggle', this).trigger('click');
                        }).on('mouseout', function () {
                            jQuery('.dropdown-toggle', this).trigger('click').blur();
                        });
                    }
                    else {
                        jQuery('.navbar .dropdown').off('mouseover').off('mouseout');
                    }
                }

                // toggle navbar hover
                toggleNavbarMethod();

                // bind resize event
                jQuery(window).resize(toggleNavbarMethod);
            });
        </script>
    </head>
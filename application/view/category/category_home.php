<?php
if (!is_null($this->taxonomyList)) {
    Controller::autoloadController("category");
    $categoryCtrl = new CategoryCtrl();
    foreach ($this->taxonomyList as $taxonomyInfo) {
        if (isset($taxonomyInfo->level) && $taxonomyInfo->level == 0) {
            $categoryCtrl->view_ajax($taxonomyInfo->term_taxonomy_id);

            ?>

            <div class = "clearfix"></div>


            <?php
        }

        ?>
        <?php
    }

    ?>
<?php }

?>
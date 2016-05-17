<?php
if (!is_null($this->taxonomyList)) {
    foreach ($this->taxonomyList as $taxonomyInfo) {
        if (isset($taxonomyInfo->level) && $taxonomyInfo->level == 0) {

            ?>
            <div class="left-sidebar panel-body">
                <h2 class="text-center"><a href="<?php echo URL . CATEGORY_CP_VIEW . $taxonomyInfo->term_taxonomy_id . "/1/" . $taxonomyInfo->slug; ?>" style="text-decoration: none; color: white;"><?php
                        if (isset($taxonomyInfo->name)) {
                            echo $taxonomyInfo->name;
                        }

                        ?></a></h2>
                <ul>
                    <?php
                    foreach ($this->taxonomyList as $taxonomyInfo2) {
                        if (isset($taxonomyInfo2->level) && $taxonomyInfo2->level == 1 && isset($taxonomyInfo2->parent) && $taxonomyInfo2->parent == $taxonomyInfo->term_taxonomy_id) {

                            ?>

                            <li><div class="icon-side-bar hidden-sm">
                                    <?php
                                    if (isset($taxonomyInfo2->images) && count($taxonomyInfo2->images) > 0) {
                                        $image = $taxonomyInfo2->images[0];

                                        ?>
                                        <img alt="<?php
                                        if (isset($taxonomyInfo2->name)) {
                                            echo $taxonomyInfo2->name;
                                        }

                                        ?>" src="<?php echo URL . $image->slider_thumb_url; ?>" class="round-anh">                                            <?php
                                         }

                                         ?>    



                                </div>
                                <a href="<?php echo URL . CATEGORY_CP_VIEW . $taxonomyInfo2->term_taxonomy_id . "/1/" . $taxonomyInfo2->slug; ?>" style="text-decoration: none;"><?php
                                    if (isset($taxonomyInfo2->name)) {
                                        echo $taxonomyInfo2->name;
                                    }

                                    ?></a>
                            </li>
                            <?php
                        }
                    }

                    ?>
                </ul>
            </div>
            <?php
        }

        ?>
        <?php
    }

    ?>
<?php }

?>
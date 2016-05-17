<?php
if (!is_null($this->taxonomyList)) {
    foreach ($this->taxonomyList as $taxonomyInfo) {
        if (isset($taxonomyInfo->level) && $taxonomyInfo->level == 0) {

            ?>
            <ul class="col-sm-3 list-unstyled">
                <li>
                    <p class="p-menu"><strong><a href="<?php echo URL . CATEGORY_CP_VIEW . $taxonomyInfo->term_taxonomy_id . "/1/" . $taxonomyInfo->slug; ?>"><?php
                                if (isset($taxonomyInfo->name)) {
                                    echo $taxonomyInfo->name;
                                }
                                ?></a></strong></p>
                </li>
                <?php
                foreach ($this->taxonomyList as $taxonomyInfo2) {
                    if (isset($taxonomyInfo2->level) && $taxonomyInfo2->level == 1 && isset($taxonomyInfo2->parent) && $taxonomyInfo2->parent == $taxonomyInfo->term_taxonomy_id) {

                        ?>
                        <li><a href="<?php echo URL . CATEGORY_CP_VIEW . $taxonomyInfo2->term_taxonomy_id . "/1/" . $taxonomyInfo2->slug; ?>"><?php
                                if (isset($taxonomyInfo2->name)) {
                                    echo $taxonomyInfo2->name;
                                }
                                ?></a></li> 
                        <?php
                    }
                }
                ?>
            </ul>
            <?php
        }
        ?>
        <?php
    }
    ?>
<?php }

?>
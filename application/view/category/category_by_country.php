<?php
if (!is_null($this->taxonomyList)) {
    foreach ($this->taxonomyList as $taxonomyInfo) {

        ?>
        <option value="<?php echo $taxonomyInfo->term_taxonomy_id; ?>"><?php echo $taxonomyInfo->name; ?></option>
        <?php
    }

    ?>
    <?php }

?>
<option selected="selected" value="0">None</option>
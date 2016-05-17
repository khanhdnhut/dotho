<?php
if (!is_null($this->taxonomyList)) {
    foreach ($this->taxonomyList as $taxonomyInfo) {
        $text = htmlspecialchars($taxonomyInfo->name);
        if (isset($this->s) && $this->s !== NULL && $this->s != "") {
            $text = str_replace($this->s, "<span class='ac_match'>" . $this->s . "</span>", $taxonomyInfo->name);
        }

        ?>

        <li class="tag_ajax ac_over"  
            tag_id="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" 
            tag_name="<?php echo htmlspecialchars($taxonomyInfo->name); ?>" 
            onclick="selectTag(this)">
            <?php echo $text; ?>
        </li>
        <?php
    }
}
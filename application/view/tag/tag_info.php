<?php
if (isset($this->tagBO) && $this->tagBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo USER_TITLE_PROFILE_OF . " " . TAG_TITLE_TAG; ?> "<strong><?php
            if (isset($this->tagBO->name)) {
                echo $this->tagBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" tag="<?php echo $this->tagBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->tagBO->name); ?>" onclick="getEditCountryPage(this)"><?php echo TAG_TITLE_EDIT; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="tag-name-wrap">
                <th>
                    <label for="name"><?php echo TITLE_NAME; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->tagBO->name)) {
                        echo htmlspecialchars($this->tagBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="tag-slug-wrap">
                <th>
                    <label for="slug"><?php echo TITLE_SLUG; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->tagBO->slug)) {
                        echo htmlspecialchars($this->tagBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="tag-parent-wrap">
                <th>
                    <label for="parent"><?php echo TITLE_PARENT; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {
                                if ($value->term_taxonomy_id != $this->tagBO->term_taxonomy_id &&
                                    $value->parent != $this->tagBO->term_taxonomy_id) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->tagBO->parent) {

                                        ?>
                                            selected="selected"
                                        <?php }

                                        ?> value="<?php
                                        if (isset($value->term_taxonomy_id)) {
                                            echo $value->term_taxonomy_id;
                                        }

                                        ?>"><?php
                                            if (isset($value->name)) {
                                                echo $value->name;
                                            }

                                            ?></option>
                                    <?php
                                }
                            }
                        }

                        ?>
                        <option value="0" <?php if ($this->tagBO->parent == 0 || $this->tagBO->parent == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo TITLE_NONE; ?></option>                                                 
                    </select>
                </td>
            </tr>

            <tr class="tag-description-wrap">
                <th>
                    <label for="description"><?php echo TITLE_DESCRIPTION; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->tagBO->description)) {
                        echo htmlspecialchars($this->tagBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>


        </tbody>
    </table>

    <script>
        function getEditCountryPage(element) {
            var tag = jQuery(element).attr("tag");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . TAG_CP_EDIT; ?>" + tag + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . TAG_CP_EDIT; ?>",
                type: "POST",
                data: {
                    tag: tag
                },
                success: function (data, textStatus, jqXHR)
                {
                    jQuery(".wrap").html(data);
                    //data: return data from server
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    //if fails      
                }
            });
        }
    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>

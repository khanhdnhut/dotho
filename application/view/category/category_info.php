<?php
if (isset($this->categoryBO) && $this->categoryBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo USER_TITLE_PROFILE_OF . " " . CATEGORY_TITLE_CATEGORY; ?> "<strong><?php
            if (isset($this->categoryBO->name)) {
                echo $this->categoryBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" category="<?php echo $this->categoryBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->categoryBO->name); ?>" onclick="getEditCategoryPage(this)"><?php echo CATEGORY_TITLE_EDIT_CATEGORY; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="category-name-wrap">
                <th>
                    <label for="name"><?php echo TITLE_NAME; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->categoryBO->name)) {
                        echo htmlspecialchars($this->categoryBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            
            <tr class="category-slug-wrap">
                <th>
                    <label for="slug"><?php echo TITLE_SLUG; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->categoryBO->slug)) {
                        echo htmlspecialchars($this->categoryBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="category-parent-wrap">
                <th>
                    <label for="parent"><?php echo CATEGORY_TITLE_PARENT; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {
                                if ($value->term_taxonomy_id != $this->categoryBO->term_taxonomy_id &&
                                    $value->parent != $this->categoryBO->term_taxonomy_id) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->categoryBO->parent) {

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
                        <option value="0" <?php if (isset($this->categoryBO->parent) && $this->categoryBO->parent == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo TITLE_NONE; ?></option>                                                
                    </select>
                </td>
            </tr>

            <tr class="category-description-wrap">
                <th>
                    <label for="description"><?php echo TITLE_DESCRIPTION; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->categoryBO->description)) {
                        echo htmlspecialchars($this->categoryBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>

            
            <tr class="images-wrap">
                <th>
                    <label for="images"><?php echo TITLE_IMAGES; ?></label>
                </th>
                <td>
                    <?php
                    if (isset($this->categoryBO->images)) {
                        foreach ($this->categoryBO->images as $image) {
                            if (isset($image->image_url)) {

                                ?>
                                <div data-p="144.50" style="float: left; margin-right: 10px;">
                                    <img data-u="thumb" src="<?php echo URL . $image->slider_thumb_url; ?>" />
                                </div>

                                <?php
                            }
                        }
                    }

                    ?>                 
                </td>
            </tr>
        <link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />

        <tr class="category-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TAG_TITLE_TAGS; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->categoryBO->tag_list) && count($this->categoryBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->categoryBO->tag_list as $tag) {
                                $tagArray[] = $tag->name;

                                ?>
                                <li><a class="tag" href="<?php echo URL . TAG_CP_INFO . $tag->term_taxonomy_id . "/" . $tag->slug; ?>/" title=""><span class="arrow2"></span><?php echo $tag->name; ?></a></li>
                                        <?php
                                    }

                                    ?>
                        </ul>
                        <?php
                    }

                    ?>

                </div>
                <input type="hidden" name="tag_list" value="<?php
                if (isset($tagArray) && count($tagArray) > 0) {
                    echo join(",", $tagArray);
                }

                ?>">
            </td>
        </tr>
    </tbody>
    </table>

    <script>
        function getEditCategoryPage(element) {
            var category = jQuery(element).attr("category");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CATEGORY_CP_EDIT; ?>" + category + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CATEGORY_CP_EDIT; ?>",
                type: "POST",
                data: {
                    category: category
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

<?php
if (isset($this->productBO) && $this->productBO != NULL) {

    ?>
    <script src="<?php echo PUBLIC_JS; ?>includes/ckeditor/ckeditor.js"></script>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo USER_TITLE_PROFILE_OF . " " . PRODUCT_TITLE_PRODUCT_1; ?> "<strong><?php
            if (isset($this->productBO->post_title)) {
                echo $this->productBO->post_title;
            }

            ?></strong>"
        <a class="page-title-action" href="#" product="<?php echo $this->productBO->ID; ?>" name="<?php echo htmlspecialchars($this->productBO->post_title); ?>" onclick="getEditProductPage(this)"><?php echo PRODUCT_TITLE_PRODUCT_1; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="product-post-title-wrap">
                <th colspan="1">
                    <label for="post_title"><?php echo PRODUCT_TITLE_NAME; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label>
                </th>
                <td colspan="3">
                    <input  disabled="disabled" type="text" class="large-text" value="<?php
                    if (isset($this->productBO->post_title)) {
                        echo htmlspecialchars($this->productBO->post_title);
                    }

                    ?>" id="post_title" name="post_title">
                </td>
            </tr>

            <tr class="product-category-id-wrap">
                <th colspan="1"><label for="category_id"><?php echo PRODUCT_TITLE_CATEGORY; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></label></th>
                <td colspan="3">
                    <select disabled="disabled" id="category_id" name="category_id"  style="min-width: 150px;">
                        <?php
                        if (isset($this->categoryList) && is_a($this->categoryList, "SplDoublyLinkedList")) {
                            $this->categoryList->rewind();
                            foreach ($this->categoryList as $value) {

                                ?>
                                <option value="<?php
                                if (isset($value->term_taxonomy_id)) {
                                    echo $value->term_taxonomy_id;
                                }

                                ?>" <?php
                                        if (isset($this->productBO->category_id) && $this->productBO->category_id == $value->term_taxonomy_id) {
                                            echo "selected='selected'";
                                        }

                                        ?>><?php
                                            if (isset($value->name)) {
                                                echo $value->name;
                                            }

                                            ?></option>
                                <?php
                            }
                        }

                        ?>
                        <option value="0" <?php
                        if (isset($this->productBO->category_id) && $this->productBO->category_id == 0) {
                            echo "selected='selected'";
                        } elseif (!isset($this->productBO->category_id)) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo TITLE_NONE; ?></option>                                                                        
                    </select>
                </td>
            </tr>
                       
            
            <tr class="images-wrap">
                <th colspan="1">
                    <label for="images"><?php echo TITLE_IMAGES; ?></label>
                </th>
                <td colspan="3">
                    <?php
                    if (isset($this->productBO->images)) {
                        foreach ($this->productBO->images as $image) {
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

            <tr class="product-post-content-wrap">
                <th colspan="1">
                    <label for="post_content"><?php echo PRODUCT_TITLE_CONTENT; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></label>
                </th>
                <td colspan="3">
                    <textarea disabled="disabled" id="post_content" name="post_content" autocomplete="off" style="height: 200px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->productBO) && isset($this->productBO->post_content)) {
                            echo htmlspecialchars($this->productBO->post_content);
                        }

                        ?></textarea>
                </td>
            </tr>

        <link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />

        <tr class="product-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TITLE_TAGS; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->productBO->tag_list) && count($this->productBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->productBO->tag_list as $tag) {
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
        function getEditProductPage(element) {
            var product = jQuery(element).attr("product");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . PRODUCT_CP_EDIT; ?>" + product + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . PRODUCT_CP_INFO; ?>",
                type: "POST",
                data: {
                    product: product
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
        CKEDITOR.replace('post_content');
    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>

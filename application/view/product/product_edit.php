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
        <?php echo TITLE_EDIT . " " . PRODUCT_TITLE_PRODUCT; ?> "<strong><?php
            if (isset($this->productBO->post_title)) {
                echo $this->productBO->post_title;
            }

            ?></strong>"
        <a class="page-title-action" ajaxlink="<?php echo URL . PRODUCT_CP_ADD_NEW; ?>" ajaxtarget=".wrap" href="#" onclick="openAjaxLink(this)" ><?php echo TITLE_ADD_NEW; ?></a>
    </h1>

    <div id="message_notice">
        <?php $this->renderFeedbackMessages(); ?>
    </div>

    <form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . PRODUCT_CP_EDIT; ?>">
        <input type="hidden" value="update" name="action">
        <input type="hidden" value="<?php
        if (isset($this->productBO->ID)) {
            echo htmlspecialchars($this->productBO->ID);
        }

        ?>" id="product" name="product">
        <table class="form-table">
            <tbody>
                <tr class="product-post-title-wrap">
                    <th colspan="1">
                        <label for="post_title"><?php echo PRODUCT_TITLE_NAME; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label>
                    </th>
                    <td colspan="3">
                        <input type="text" class="large-text" value="<?php
                        if (isset($this->productBO->post_title)) {
                            echo htmlspecialchars($this->productBO->post_title);
                        }

                        ?>" id="post_title" name="post_title">
                    </td>
                </tr>

                <tr class="product-category-id-wrap">
                    <th><label for="category_id"><?php echo PRODUCT_TITLE_CATEGORY; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></label></th>
                    <td>
                        <select id="category_id" name="category_id"  style="min-width: 150px;">
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
                        <input type="file" id="images" name="images[]" accept=".jpg, .png, .jpeg"  multiple>
                        <p class="images"  style="float: left; width: 100%; margin-bottom: 30px;"><?php echo PRODUCT_DESC_IMAGES; ?></p>
                        <input type="hidden" name="image_delete_list">
                        <?php
                        if (isset($this->productBO->images)) {
                            foreach ($this->productBO->images as $image) {
                                if (isset($image->image_url)) {

                                    ?>
                                    <div data-p="144.50" style="float: left; margin-right: 10px;">
                                        <img data-u="thumb" src="<?php echo URL . $image->slider_thumb_url; ?>" />
                                        <div class="row-actions widefat" style="text-align: center;">
                                            <span class="delete">
                                                <a onclick="deleteImage(this)" image_id="<?php echo $image->image_id; ?>" class="submitdelete" href="#">Delete                                        </a>
                                            </span>
                                        </div>

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
                </tr>
                <tr class="product-post-content-wrap">                    
                    <td colspan="4">
                        <textarea id="post_content" name="post_content" autocomplete="off" style="height: 200px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->productBO) && isset($this->productBO->post_content)) {
                                echo htmlspecialchars($this->productBO->post_content);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="product-tags-wrap">   
                    <th colspan="1">
                        <label for="tags"><?php echo TITLE_TAGS; ?></label>
                    </th>
                    <td colspan="3">
                        <input style="min-width: 200px;" type="text" value="" autocomplete="off" size="16" class="newtag form-input-tip" name="tag_input" id="tags" onkeyup="searchTagAjax(this.value)">
                        <ul id="livesearch" class="ac_results" style="display: block;">
                        </ul>                    
                        <input type="button" value="Add" class="button tagadd" onclick="addInputTag()">
                        <p id="new-tag-post_tag-desc" class="howto">Separate tags with commas</p>
                        <div id="tagchecklist" class="tagchecklist">
                            <?php
                            if (isset($this->productBO->tag_list) && count($this->productBO->tag_list) > 0) {
                                $tagArray = array();
                                foreach ($this->productBO->tag_list as $tag) {
                                    $tagArray[] = $tag->name;

                                    ?>
                                    <span><a onclick="removeTag(this)" tag_name="<?php echo $tag->name; ?>" class="ntdelbutton" tabindex="0">X</a>&nbsp;<?php echo $tag->name; ?></span>
                                    <?php
                                }
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

        <p class="submit"><input type="submit" value="<?php echo TITLE_UPDATE; ?>" class="button button-primary" id="submit" name="submit"></p>
    </form>
    <script>

        function deleteImage(element) {

            if (confirm('<?php echo CONFIRM_DELETE_IMAGE . CONFIRM_DELETE_CANCEL_OK; ?>')) {
                var image_id = jQuery(element).attr("image_id");
                if (image_id != undefined) {
                    image_id = image_id.trim();
                    if (image_id != "") {
                        jQuery(element).parent().parent().parent().remove();
                        var image_delete_list = jQuery('#form-your-profile input[name="image_delete_list"]').val();
                        if (image_delete_list == "") {
                            var image_delete_array = [];
                        } else {
                            var image_delete_array = image_delete_list.split(",");
                        }

                        if (image_delete_array.indexOf(image_id) == -1) {
                            image_delete_array.push(image_id);
                            jQuery('#form-your-profile input[name="image_delete_list"]').val(image_delete_array.join(","));
                        }
                    }
                }
            }
        }
        window.scrollTo(0, 0);

        function getDoc(frame) {
            var doc = null;
            // IE8 cascading access check
            try {
                if (frame.contentWindow) {
                    doc = frame.contentWindow.document;
                }
            } catch (err) {
            }
            if (doc) { // successful getting content
                return doc;
            }
            try { // simply checking may throw in ie8 under ssl or mismatched protocol
                doc = frame.contentDocument ? frame.contentDocument : frame.document;
            } catch (err) {
                // last attempt
                doc = frame.document;
            }
            return doc;
        }

        function hideMessageSuccess() {
            jQuery("#message-success").hide();
        }
        function hideMessageError() {
            jQuery("#message-error").hide();
        }

        function noticeError(message) {
            document.getElementById('message_notice').innerHTML =
                    "<div class='error notice is-dismissible' id='message-error'><p>" + message + "</p>"
            "   <button class='notice-dismiss' type='button' onclick='hideMessageError()'>" + "       <span class='screen-reader-text'>Dismiss this notice.</span>" +
                    "   </button>" +
                    "</div>";
            window.scrollTo(0, 0);
        }

        function validateFormEditProduct() {
            if (jQuery('#form-your-profile input[name="post_title"]').val() == "") {
                noticeError("<?php echo PRODUCT_ERROR_TITLE_EMPTY; ?>");
                return false;
            }
            if (jQuery('#form-your-profile textarea[name="post_content"]').val() == "") {
                noticeError("<?php echo PRODUCT_ERROR_CONTENT_EMPTY; ?>");
                return false;
            }
            return true;
        }

        jQuery("#form-your-profile").submit(function (e) {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            if (!validateFormEditProduct()) {
                return;
            }
            var name = jQuery('#form-your-profile input[name="post_title"]').val();
            if (confirm('<?php echo PRODUCT_CONFIRM_EDIT_INFO_PRODUCT; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
                var formObj = jQuery(this);
                var formURL = formObj.attr("action");
                if (window.FormData !== undefined)  // for HTML5 browsers
                {
                    var formData = new FormData(this);
                    jQuery.ajax({
                        url: formURL,
                        type: "POST",
                        data: formData,
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data, textStatus, jqXHR)
                        {
                            jQuery(".wrap").html(data);
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                        }
                    });
                    e.preventDefault();
                }
                else  //for olden browsers
                {
                    //generate a random id
                    var iframeId = "unique" + (new Date().getTime());
                    //create an empty iframe
                    var iframe = jQuery('<iframe src="javascript:false;" name="' + iframeId + '" />');
                    //hide it
                    iframe.hide();
                    //set form target to iframe
                    formObj.attr("target", iframeId);
                    //Add iframe to body
                    iframe.appendTo("body");
                    iframe.load(function (e)
                    {
                        var doc = getDoc(iframe[0]);
                        var docRoot = doc.body ? doc.body : doc.documentElement;
                        var data = docRoot.innerHTML;
                        jQuery(".wrap").html(data);
                        //data return from server.

                    });
                }
            }
        });
        function searchTagAjax(str) {
            if (str.length == 0) {
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("livesearch").innerHTML = xmlhttp.responseText;
                    document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET", "<?php echo URL . TAG_CP_SEARCH_AJAX; ?>" + str, true);
            xmlhttp.send();

        }

        function addInputTag() {
            var tag_name = jQuery('#form-your-profile input[name="tag_input"]').val().trim();
            if (tag_name != "") {
                var tag_add_array = tag_name.split(",");
                for (var i = 0; i < tag_add_array.length; i++) {
                    var name = tag_add_array[i];
                    if (name != undefined) {
                        name = name.trim();
                        if (name != "") {
                            addTag(name);
                        }
                    }
                }
            }
        }

        function selectTag(element) {
            var tag_name = jQuery(element).attr("tag_name");
            if (tag_name != undefined) {
                tag_name = tag_name.trim();
                if (tag_name != "") {
                    addTag(tag_name);
                }
            }
        }

        function addTag(tag_name) {
            var tag_list = jQuery('#form-your-profile input[name="tag_list"]').val();
            if (tag_list == "") {
                var tag_array = [];
            } else {
                var tag_array = tag_list.split(",");
            }

            if (tag_array.indexOf(tag_name) == -1) {
                tag_array.push(tag_name);
                document.getElementById("tagchecklist").innerHTML = document.getElementById("tagchecklist").innerHTML + "<span><a tabindex='0' class='ntdelbutton' tag_name='" + tag_name + "' onclick='removeTag(this)'>X</a>&nbsp;" + tag_name + "</span>";
                jQuery('#form-your-profile input[name="tag_list"]').val(tag_array.join(","));
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px solid #A5ACB2";
                jQuery('#form-your-profile input[name="tag_input"]').val("");
            } else {
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px solid #A5ACB2";
                jQuery('#form-your-profile input[name="tag_input"]').val("");
            }
        }

        function removeTag(element) {
            var tag_name = jQuery(element).attr("tag_name");
            if (tag_name != undefined) {
                tag_name = tag_name.trim();
                if (tag_name != "") {
                    jQuery(element).parent().remove();
                    var tag_list = jQuery('#form-your-profile input[name="tag_list"]').val();
                    if (tag_list == "") {
                        var tag_array = [];
                    } else {
                        var tag_array = tag_list.split(",");
                    }

                    if (tag_array.indexOf(tag_name) != -1) {
                        tag_array.splice(tag_array.indexOf(tag_name), 1);
                        jQuery('#form-your-profile input[name="tag_list"]').val(tag_array.join(","));
                        document.getElementById("livesearch").innerHTML = "";
                        document.getElementById("livesearch").style.border = "0px solid #A5ACB2";
                    }
                }
            }
        }

        CKEDITOR.replace('post_content');

    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>

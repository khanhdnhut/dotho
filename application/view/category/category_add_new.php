<script src="<?php echo PUBLIC_JS; ?>includes/ckeditor/ckeditor.js"></script>
<style>
    .form-table th {
        font-weight: 100;   
    }
</style>
<h1>
    <?php echo CATEGORY_TITLE_ADD_NEW; ?>
</h1>
<div id="message_notice">
    <?php $this->renderFeedbackMessages(); ?>
</div>
<form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . CATEGORY_CP_ADD_NEW; ?>">
    <input type="hidden" value="addNew" name="action">
    <input type="hidden" value="ajax" name="ajax">
    <table class="form-table">
        <tbody>
            <tr class="category-name-wrap">
                <th>
                    <label for="name"><?php echo TITLE_NAME; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php
                    if (isset($this->para->name)) {
                        echo htmlspecialchars($this->para->name);
                    }

                    ?>" id="name" name="name">
                    <p class="description"><?php echo CATEGORY_DESC_CATEGORY_NAME; ?></p>
                </td>
            </tr>

            
            <tr class="category-slug-wrap">
                <th>
                    <label for="slug"><?php echo TITLE_SLUG; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php
                    if (isset($this->para->slug)) {
                        echo htmlspecialchars($this->para->slug);
                    }

                    ?>" id="slug" name="slug">
                    <p class="description"><?php echo CATEGORY_DESC_SLUG; ?></p>
                </td>
            </tr>

            <tr class="category-parent-wrap"><th><label for="parent"><?php echo CATEGORY_TITLE_PERENT; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label></th>
                <td>
                    <select id="parent" name="parent" >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {

                                ?>
                                <option value="<?php
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

                        ?>
                        <option value="0" selected="selected"><?php echo TITLE_NONE; ?></option>                                                                        
                    </select>
                    <span class="description"><?php echo CATEGORY_DESC_PERENT; ?></span>
                </td>
            </tr>

            <tr class="category-description-wrap">
                <th>
                    <label for="description"><?php echo TITLE_DESCRIPTION; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php
                    if (isset($this->para) && isset($this->para->description)) {
                        echo htmlspecialchars($this->para->description);
                    }

                    ?>" id="description" name="description">
                    <p class="description"><?php echo CATEGORY_DESC_DESCRIPTION; ?></p>
                </td>
            </tr>

            
            <tr class="images-wrap">
                <th>
                    <label for="images"><?php echo TITLE_IMAGES; ?></label>
                </th>
                <td>
                    <input type="file" id="images" name="images[]" accept=".jpg, .png, .jpeg"  multiple>                    
                    <p class="images"><?php echo CATEGORY_DESC_IMAGES; ?></p>
                </td>
            </tr>
            <tr class="category-tags-wrap">   
                <th colspan="1">
                    <label for="tags"><?php echo TAG_TITLE_TAGS; ?></label>
                </th>
                <td colspan="3">
                    <input style="min-width: 200px;" type="text" value="" autocomplete="off" size="16" class="newtag form-input-tip" name="tag_input" id="tags" onkeyup="searchTagAjax(this.value)">
                    <ul id="livesearch" class="ac_results" style="display: block;">
                    </ul>                    
                    <input type="button" value="Add" class="button tagadd" onclick="addInputTag()">
                    <p id="new-tag-post_tag-desc" class="howto">Separate tags with commas</p>
                    <div id="tagchecklist" class="tagchecklist"></div>
                    <input type="hidden" name="tag_list">
                </td>
            </tr>
        </tbody>
    </table>

    <p class="submit"><input type="submit" value="<?php echo CATEGORY_TITLE_ADD_NEW; ?>" class="button button-primary" id="submit" name="submit"></p>
</form>
<script>
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
                            "   <button class='notice-dismiss' type='button' onclick='hideMessageError()'>" +
                                    "       <span class='screen-reader-text'>Dismiss this notice.</span>" +
                                    "   </button>" +
                                    "</div>";
                            window.scrollTo(0, 0);
                        }

                        jQuery('#form-your-profile input[name="name"]').change(function () {
                            jQuery('#form-your-profile input[name="slug"]').val(createSlug(jQuery('#form-your-profile input[name="name"]').val()));
                        })

                        function validateFormAddNewCategory() {
                            if (jQuery('#form-your-profile input[name="name"]').val() == "") {
                                noticeError("<?php echo USER_ERROR_NAME_EMPTY; ?>");
                                return false;
                            }
                            if (jQuery('#form-your-profile input[name="slug"]').val() == "") {
                                noticeError("<?php echo USER_ERROR_SLUG_EMPTY; ?>");
                                return false;
                            }
                            return true;
                        }

                        jQuery("#form-your-profile").submit(function (e) {
                            e.preventDefault();

                            if (!validateFormAddNewCategory()) {
                                return;
                            }
                            var name = jQuery('#form-your-profile input[name="name"]').val();
                            if (confirm('<?php echo CATEGORY_CONFIRM_ADD_NEW_CATEGORY; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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

</script>

<?php
if (isset($this->tagBO) && $this->tagBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo USER_TITLE_EDIT_PROFILE_OF . " " . TAG_TITLE_TAG; ?> "<strong><?php
            if (isset($this->tagBO->name)) {
                echo $this->tagBO->name;
            }

            ?></strong>"
        <a class="page-title-action" ajaxlink="<?php echo URL . TAG_CP_ADD_NEW; ?>" ajaxtarget=".wrap" href="#" onclick="openAjaxLink(this)" ><?php echo TITLE_ADD_NEW; ?></a>
    </h1>

    <div id="message_notice">
        <?php $this->renderFeedbackMessages(); ?>
    </div>

    <form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . TAG_CP_EDIT; ?>">
        <input type="hidden" value="update" name="action">
        <input type="hidden" value="<?php
        if (isset($this->tagBO->term_taxonomy_id)) {
            echo htmlspecialchars($this->tagBO->term_taxonomy_id);
        }

        ?>" id="tag" name="tag">
        <table class="form-table">
            <tbody>
                <tr class="tag-name-wrap">
                    <th>
                        <label for="name"><?php echo TITLE_NAME; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->tagBO->name)) {
                            echo htmlspecialchars($this->tagBO->name);
                        }

                        ?>" id="name" name="name">
                        <p class="description"><?php echo TAG_DESC_NAME; ?></p>
                    </td>
                </tr>
                <tr class="tag-slug-wrap">
                    <th>
                        <label for="slug"><?php echo TITLE_SLUG; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->tagBO->slug)) {
                            echo htmlspecialchars($this->tagBO->slug);
                        }

                        ?>" id="slug" name="slug">
                        <p class="description"><?php echo TAG_DESC_SLUG; ?></p>
                    </td>
                </tr>

                <tr class="tag-parent-wrap"><th><label for="parent"><?php echo TAG_TITLE_PARENT; ?> </label></th>
                    <td>
                        <select id="parent" name="parent" >
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
                        <p class="description"><?php echo TAG_DESC_PARENT; ?></p>
                    </td>
                </tr>

                <tr class="tag-description-wrap">
                    <th>
                        <label for="description"><?php echo TITLE_DESCRIPTION; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->tagBO->description)) {
                            echo htmlspecialchars($this->tagBO->description);
                        }

                        ?>" id="description" name="description">
                        <p class="description"><?php echo TAG_DESC_DESCRIPTION; ?></p>
                    </td>
                </tr>

            </tbody>
        </table>

        <p class="submit"><input type="submit" value="<?php echo TAG_TITLE_UPDATE; ?>" class="button button-primary" id="submit" name="submit"></p>
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
            "   <button class='notice-dismiss' type='button' onclick='hideMessageError()'>" + "       <span class='screen-reader-text'>Dismiss this notice.</span>" +
                    "   </button>" +
                    "</div>";
            window.scrollTo(0, 0);
        }

        jQuery('#form-your-profile input[name="name"]').change(function () {
            jQuery('#form-your-profile input[name="slug"]').val(createSlug(jQuery('#form-your-profile input[name="name"]').val()));
        })
        
        function validateFormEditTag() {
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
            if (!validateFormEditTag()) {
                return;
            }
            var name = jQuery('#form-your-profile input[name="name"]').val();
            if (confirm('<?php echo TAG_CONFIRM_EDIT_INFO; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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
    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>

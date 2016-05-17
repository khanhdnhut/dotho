<?php
if (isset($this->userBO) && $this->userBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo USER_TITLE_EDIT_PROFILE_OF . " " . USER_TITLE_USER; ?> "<strong><?php
            if (isset($this->userBO->user_login) && $this->userBO->user_id != Session::get('user_id')) {
                echo $this->userBO->user_login;
            } else {
                echo USER_TITLE_YOU;
            }

            ?></strong>"
        <a class="page-title-action" ajaxlink="<?php echo URL . USER_CP_ADD_NEW; ?>" ajaxtarget=".wrap" href="#" onclick="openAjaxLink(this)" ><?php echo TITLE_ADD_NEW; ?></a>
    </h1>

    <div id="message_notice">
        <?php $this->renderFeedbackMessages(); ?>
    </div>

    <form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . USER_CP_EDIT; ?>">
        <input type="hidden" value="update" name="action">
        <input type="hidden" value="<?php
        if (isset($this->userBO->user_id)) {
            echo htmlspecialchars($this->userBO->user_id);
        }

        ?>" id="user" name="user">
        <h2><?php echo TITLE_NAME; ?></h2>
        <table class="form-table">
            <tbody>
                <tr class="user-user-login-wrap">
                    <th>
                        <label for="user_login"><?php echo USER_TITLE_USERNAME; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" disabled="disabled" value="<?php
                        if (isset($this->userBO->user_login)) {
                            echo htmlspecialchars($this->userBO->user_login);
                        }

                        ?>" id="user_login" name="user_login">
                        <span class="description"><?php echo USER_DESC_USER_NAME; ?></span>
                    </td>
                </tr>

                <tr class="user-role-wrap"><th><label for="role">Role</label></th>
                    <td>
                        <select id="role" name="role" <?php
                        if (isset($this->userBO->user_login) && $this->userBO->user_id == Session::get('user_id')) {
                            echo "disabled='disabled'";
                        }

                        ?> >
                            <option value="<?php echo USER_VALUE_EDITOR; ?>" <?php
                            if (isset($this->para) && isset($this->para->role)) {
                                if ($this->para->role == USER_VALUE_EDITOR) {
                                    echo "selected='selected'";
                                }
                            } elseif (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == USER_VALUE_EDITOR) {
                                echo "selected='selected'";
                            }

                            ?> ><?php echo USER_TITLE_EDITOR; ?></option>
                            <option value="<?php echo USER_VALUE_SUBSCRIBER; ?>" <?php
                            if (isset($this->para) && isset($this->para->role)) {
                                if ($this->para->role == USER_VALUE_SUBSCRIBER) {
                                    echo "selected='selected'";
                                }
                            } elseif (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == USER_VALUE_SUBSCRIBER) {
                                echo "selected='selected'";
                            }

                            ?>><?php echo USER_TITLE_SUBSCRIBER; ?></option>
                            <option value="<?php echo USER_VALUE_CONTRIBUTOR; ?>" <?php
                            if (isset($this->para) && isset($this->para->role)) {
                                if ($this->para->role == USER_VALUE_CONTRIBUTOR) {
                                    echo "selected='selected'";
                                }
                            } elseif (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == USER_VALUE_CONTRIBUTOR) {
                                echo "selected='selected'";
                            }

                            ?>><?php echo USER_TITLE_CONTRIBUTOR; ?></option>
                            <option value="<?php echo USER_VALUE_AUTHOR; ?>" <?php
                            if (isset($this->para) && isset($this->para->role)) {
                                if ($this->para->role == USER_VALUE_AUTHOR) {
                                    echo "selected='selected'";
                                }
                            } elseif (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == USER_VALUE_AUTHOR) {
                                echo "selected='selected'";
                            }

                            ?>><?php echo USER_TITLE_AUTHOR; ?></option>
                            <option value="<?php echo USER_VALUE_ADMIN; ?>" <?php
                            if (isset($this->para) && isset($this->para->role)) {
                                if ($this->para->role == USER_VALUE_ADMIN) {
                                    echo "selected='selected'";
                                }
                            } elseif (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == USER_VALUE_ADMIN) {
                                echo "selected='selected'";
                            }

                            ?>><?php echo USER_TITLE_ADMIN; ?></option>
                        </select>
                    </td>
                </tr>

                <tr class="user-first-name-wrap">
                    <th>
                        <label for="first_name"><?php echo USER_TITLE_FIRST_NAME; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->para) && isset($this->para->first_name)) {
                            echo htmlspecialchars($this->para->first_name);
                        } elseif (isset($this->userBO->first_name)) {
                            echo htmlspecialchars($this->userBO->first_name);
                        }

                        ?>" id="first_name" name="first_name">
                    </td>
                </tr>

                <tr class="user-last-name-wrap">
                    <th>
                        <label for="last_name"><?php echo USER_TITLE_LAST_NAME; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->para) && isset($this->para->last_name)) {
                            echo htmlspecialchars($this->para->last_name);
                        } elseif (isset($this->userBO->last_name)) {
                            echo htmlspecialchars($this->userBO->last_name);
                        }

                        ?>" id="last_name" name="last_name">
                    </td>
                </tr>

                <tr class="user-nickname-wrap">
                    <th>
                        <label for="nickname"><?php echo USER_TITLE_NICKNAME; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->para) && isset($this->para->nickname)) {
                            echo htmlspecialchars($this->para->nickname);
                        } elseif (isset($this->userBO->nickname)) {
                            echo htmlspecialchars($this->userBO->nickname);
                        }

                        ?>" id="nickname" name="nickname">
                    </td>
                </tr>

                <tr class="user-display-name-wrap">
                    <th>
                        <label for="display_name"><?php echo USER_TITLE_DISPLAY_NAME; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->para) && isset($this->para->display_name)) {
                            echo htmlspecialchars($this->para->display_name);
                        } elseif (isset($this->userBO->display_name)) {
                            echo htmlspecialchars($this->userBO->display_name);
                        }

                        ?>" id="display_name" name="display_name">
                    </td>
                </tr>
            </tbody>
        </table>

        <h2><?php echo USER_TITLE_CONTACT_INFO; ?></h2>

        <table class="form-table">
            <tbody>
                <tr class="user-email-wrap">
                    <th>
                        <label for="email"><?php echo USER_TITLE_EMAIL; ?> <span style="color: red;" class="description"><?php echo TITLE_REQUIRED; ?></span></label>
                    </th>
                    <td>
                        <input type="email" class="regular-text ltr" value="<?php
                        if (isset($this->para) && isset($this->para->email)) {
                            echo htmlspecialchars($this->para->email);
                        } elseif (isset($this->userBO->user_email)) {
                            echo htmlspecialchars($this->userBO->user_email);
                        }

                        ?>" id="email" name="email">
                    </td>
                </tr>

                <tr class="user-url-wrap">
                    <th>
                        <label for="url"><?php echo USER_TITLE_USER_WEBSITE; ?></label>
                    </th>
                    <td>
                        <input type="url" class="regular-text code" value="<?php
                        if (isset($this->para) && isset($this->para->url)) {
                            echo htmlspecialchars($this->para->url);
                        } elseif (isset($this->userBO->user_url)) {
                            echo htmlspecialchars($this->userBO->user_url);
                        }

                        ?>" id="url" name="url">
                    </td>
                </tr>

            </tbody>
        </table>

        <h2><?php echo USER_TITLE_ABOUT; ?></h2>

        <table class="form-table">
            <tbody>
                <tr class="user-description-wrap">
                    <th>
                        <label for="description"><?php echo USER_TITLE_BIOGRAPHICAL_INFO; ?></label>
                    </th>
                    <td>
                        <textarea cols="30" rows="5" id="description" name="description"><?php
                            if (isset($this->para) && isset($this->para->description)) {
                                echo htmlspecialchars($this->para->description);
                            } elseif (isset($this->userBO->description)) {
                                echo htmlspecialchars($this->userBO->description);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="user-profile-picture">
                    <th><?php echo USER_TITLE_PROFILE_PICTURE; ?></th>
                    <td>
                        <img width="96" height="96" class="avatar avatar-96 photo" srcset="<?php
                        if (isset($this->userBO->avatar_url)) {
                            echo URL . htmlspecialchars($this->userBO->avatar_url);
                        } else {
                            echo URL . USER_VALUE_AVATAR_DEFAULT;
                        }

                        ?>" src="<?php
                             if (isset($this->userBO->avatar_url)) {
                                 echo URL . htmlspecialchars($this->userBO->avatar_url);
                             } else {
                                 echo URL . USER_VALUE_AVATAR_DEFAULT;
                             }

                             ?>" alt="">
                        <br>
                        <input type="file" id="avatar" name="avatar" accept=".jpg, .png, .jpeg" required>
                    </td>
                </tr>

            </tbody>
        </table>
        <h2><?php echo USER_TITLE_ACCOUNT_MANAGEMENT; ?></h2>
        <table class="form-table">
            <tbody>
                <tr class="user-pass1-wrap" id="password">
                    <th>
                        <label for="pass1-text"><?php echo USER_TITLE_NEW_PASSWORD; ?></label>
                    </th>
                    <td>
                        <button id="button-generate-password" class="button button-secondary wp-generate-pw hide-if-no-js" type="button" style="<?php
                        if (isset($this->para) && isset($this->para->pass1) && !is_null($this->para->pass1) && $this->para->pass1 != "") {
                            echo "display: none;";
                        } else {
                            echo "display: inline-block;";
                        }

                        ?>"><?php echo USER_TITLE_GENERATE_PASSWORD; ?></button>
                        <div id="area-generate-password" class="wp-pwd hide-if-js" style="<?php
                        if (isset($this->para) && isset($this->para->pass1) && !is_null($this->para->pass1) && $this->para->pass1 != "") {
                            echo "display: inline-block;";
                        } else {
                            echo "display: none;";
                        }

                        ?>">
                            <span class="password-input-wrapper show-password">
                                <input type="password" aria-describedby="pass-strength-result" data-pw="" autocomplete="off" value="<?php
                                if (isset($this->para) && isset($this->para->pass1)) {
                                    echo htmlspecialchars($this->para->pass1);
                                }

                                ?>" class="regular-text" id="pass1" name="pass1" style="display: none;">
                                <input value="<?php
                                if (isset($this->para) && isset($this->para->pass1_text)) {
                                    echo htmlspecialchars($this->para->pass1_text);
                                }

                                ?>" type="text" id="pass1-text" name="pass1-text" autocomplete="off" class="regular-text strong" style="display: none;">
                            </span>
                            <button id="button-hide-password" aria-label="Hide password" data-toggle="0" class="button button-secondary wp-hide-pw hide-if-no-js" type="button">
                                <span class="dashicons dashicons-hidden"></span>
                                <span class="text"><?php echo TITLE_HIDE; ?></span>
                            </button>
                            <button id="button-show-password" aria-label="Show password" data-toggle="0" class="button button-secondary wp-hide-pw hide-if-no-js" type="button">
                                <span class="dashicons dashicons-visibility"></span>
                                <span class="text"><?php echo TITLE_SHOW; ?></span>
                            </button>
                            <button id="button-generate-password-random" aria-label="<?php echo USER_TITLE_GENERATE_PASSWORD; ?>" data-toggle="0" class="button button-secondary wp-cancel-pw hide-if-no-js" type="button">
                                <span class="text"><?php echo USER_TITLE_GENERATE_PASSWORD; ?></span>
                            </button>                            
                            <button id="button-cancle-generate-password" aria-label="Cancel password change" data-toggle="0" class="button button-secondary wp-cancel-pw hide-if-no-js" type="button">
                                <span class="text"><?php echo TITLE_CANCEL; ?></span>
                            </button>
                            <div aria-live="polite" id="pass-strength-result" style="" class="strong">&nbsp;</div>
                        </div>
                    </td>
                </tr>
                <tr id="confirm_pw_weak" class="user-pass2-wrap" style="<?php
                if (!(isset($this->para->pass1_text) && $this->para->pass1_text != NULL &&
                    $this->para->pass1_text != "")) {
                    if (isset($this->para) && isset($this->para->pw_weak) && $this->para->pw_weak == "confirm") {
                        echo "";
                    } else {
                        if (isset($this->para) && isset($this->para->pass1_text) && $this->para->pass1_text != NULL && $this->para->pass1_text != "") {
                            echo "";
                        } else {
                            echo "display: none;";
                        }
                    }
                } else {
                    echo "display: none;";
                }

                ?>">
                    <th><?php echo USER_TITLE_CONFIRM_PASSWORD; ?></th>
                    <td>
                        <label>
                            <input type="checkbox" class="pw-checkbox" name="pw_weak" value="confirm"> <?php echo USER_DESC_CONFIRM_PASSWORD; ?> </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" value="<?php echo USER_TITLE_UPDATE_USER; ?>" class="button button-primary" id="submit" name="submit"></p>
    </form>
    <script>
        window.scrollTo(0, 0);

        function getDoc(frame) {
            var doc = null;             // IE8 cascading access check
            try {
                if (frame.contentWindow) {
                    doc = frame.contentWindow.document;
                }
            } catch (err) {
            }
            if (doc) { // successful getting content                 return doc;
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
        function validateFormEditUser() {
            if (jQuery('#form-your-profile input[name="email"]').val() == "") {
                noticeError("<?php echo USER_ERROR_EMAIL_IS_EMPTY; ?>");
                return false;
            }
            return true;
        }
        jQuery("#form-your-profile").submit(function (e) {
            e.preventDefault();
            if (!validateFormEditUser()) {
                return;
            }
            var name = jQuery('#form-your-profile input[name="user_login"]').val();

            if (confirm('<?php echo USER_CONFIRM_EDIT_INFO_USER; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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
        function analyzePassword(txtpass) {
            var desc = new Array();
            var resultCheck;
            desc[0] = "Very Weak";
            desc[1] = "Weak";
            desc[2] = "Better";
            desc[3] = "Medium";
            desc[4] = "Strong";
            desc[5] = "Strongest";
            var score = 0;             //if txtpass bigger than 6 give 1 point
            if (txtpass.length > 8) {
                score++;
                //if txtpass has both lower and uppercase characters give 1 point
                if ((txtpass.match(/[a-z]/)) && (txtpass.match(/[A-Z]/)))
                    score++;
                //if txtpass has at least one number give 1 point
                if (txtpass.match(/\d+/))
                    score++;
                //if txtpass has at least one special caracther give 1 point
                //!@#$%^&*()_+~`|}{[]\:;?><,./-=
                if (txtpass.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,+,`,|,},{,(,),\],\[,\\,\/,<,>,=,:,;,\,]/))
                    score++;
                //if txtpass bigger than 12 give another 1 point
                if (txtpass.length > 12)
                    score++;
                resultCheck = desc[score];
            } else {
                resultCheck = "Password Should be Minimum 8 Characters";
            }
            return resultCheck;
        }
        function chkPasswordStrength(txtpass)
        {
            var desc = new Array();
            desc[0] = "Very Weak";
            desc[1] = "Weak";
            desc[2] = "Better";
            desc[3] = "Medium";
            desc[4] = "Strong";
            desc[5] = "Strongest";
            var resultCheck = analyzePassword(txtpass);
            document.getElementById("pass-strength-result").innerHTML = resultCheck;
            if (resultCheck == desc[5]) {
                jQuery("#pass-strength-result").removeClass().addClass("strong");
                jQuery("#pass-strength-result").show();
                jQuery("#confirm_pw_weak").hide();
            } else if (resultCheck == desc[4] || resultCheck == desc[3]) {
                jQuery("#pass-strength-result").removeClass().addClass("good");
                jQuery("#pass-strength-result").show();
                jQuery("#confirm_pw_weak").hide();
            } else if (resultCheck == desc[2] || resultCheck == desc[1]) {
                jQuery("#pass-strength-result").removeClass().addClass("bad");
                jQuery("#confirm_pw_weak").show();
                jQuery("#pass-strength-result").show();
            } else {
                jQuery("#pass-strength-result").removeClass().addClass("short");
                jQuery("#confirm_pw_weak").show();
                jQuery("#pass-strength-result").show();
            }
        }

        jQuery("input[name='pass1']").keyup(function () {
            jQuery("input[name='pass1-text']").val(jQuery("input[name='pass1']").val());
            chkPasswordStrength(jQuery("input[name='pass1']").val());
        });
        jQuery("input[name='pass1-text']").keyup(function () {
            jQuery("input[name='pass1']").val(jQuery("input[name='pass1-text']").val());
            chkPasswordStrength(jQuery("input[name='pass1']").val());
        });
        function password_generator(len) {
            var length = (len) ? (len) : (10);
            var string = "abcdefghijklmnopqrstuvwxyz"; //to upper 
            var numeric = '0123456789';
            var punctuation = '!@#$%^&*()_+~`|}{[]\:;?><,./-=';
            var password = "";
            var character = "";
            var crunch = true;
            while (password.length < length) {
                entity1 = Math.ceil(string.length * Math.random() * Math.random());
                entity2 = Math.ceil(numeric.length * Math.random() * Math.random());
                entity3 = Math.ceil(punctuation.length * Math.random() * Math.random());
                hold = string.charAt(entity1);
                hold = (entity1 % 2 == 0) ? (hold.toUpperCase()) : (hold);
                character += hold;
                character += numeric.charAt(entity2);
                character += punctuation.charAt(entity3);
                password = character;
            }
            return password;
        }

    <?php if (isset($this->para) && isset($this->para->pass1) && !is_null($this->para->pass1) && $this->para->pass1 != "") { ?>
            chkPasswordStrength('<?php echo $this->para->pass1; ?>');
            jQuery("#button-generate-password").hide();
            jQuery("#area-generate-password").show();
            jQuery("input[name='pass1']").hide();
            jQuery("input[name='pass1-text']").show();
            jQuery("#button-hide-password").show();
            jQuery("#button-show-password").hide();
        <?php
    } else {

        ?>
            jQuery("#button-generate-password").show();
            jQuery("#area-generate-password").hide();
            jQuery("input[name='pass1']").hide();
            jQuery("input[name='pass1-text']").show();
        <?php
    }

    ?>
        jQuery("#button-hide-password").click(function () {
            jQuery("#button-generate-password").hide();
            jQuery("#area-generate-password").show();
            jQuery("input[name='pass1']").show();
            jQuery("input[name='pass1']").val(jQuery("input[name='pass1-text']").val());
            jQuery("input[name='pass1-text']").hide();
            chkPasswordStrength(jQuery("input[name='pass1']").val());
            jQuery("#button-hide-password").hide();
            jQuery("#button-show-password").show();
            jQuery("input[name='pass1']").focus();
        });
        jQuery("#button-show-password").click(function () {
            jQuery("#button-generate-password").hide();
            jQuery("#area-generate-password").show();
            jQuery("input[name='pass1']").hide();
            jQuery("input[name='pass1-text']").val(jQuery("input[name='pass1']").val());
            jQuery("input[name='pass1-text']").show();
            chkPasswordStrength(jQuery("input[name='pass1']").val());
            jQuery("#button-hide-password").show();
            jQuery("#button-show-password").hide();
            jQuery("input[name='pass1-text']").focus();
        });
        jQuery("#button-cancle-generate-password").click(function () {
            jQuery("#button-generate-password").show();
            jQuery("#area-generate-password").hide();
            jQuery("input[name='pass1']").val('');
            jQuery("input[name='pass1-text']").val('');
        });
        jQuery("#button-generate-password").click(function () {
            jQuery("#button-generate-password").hide();
            jQuery("#area-generate-password").show();
            var pw_random = password_generator();
            jQuery("input[name='pass1']").val(pw_random);
            jQuery("input[name='pass1-text']").val(pw_random);
            jQuery("input[name='pass1']").hide();
            jQuery("input[name='pass1-text']").show();
            chkPasswordStrength(jQuery("input[name='pass1']").val());
            jQuery("#button-hide-password").show();
            jQuery("#button-show-password").hide();
            jQuery("input[name='pass1-text']").focus();
        });
        jQuery("#button-generate-password-random").click(function () {
            jQuery("#button-generate-password").hide();
            jQuery("#area-generate-password").show();
            var pw_random = password_generator();
            jQuery("input[name='pass1']").val(pw_random);
            jQuery("input[name='pass1-text']").val(pw_random);
            jQuery("input[name='pass1']").hide();
            jQuery("input[name='pass1-text']").show();
            chkPasswordStrength(jQuery("input[name='pass1']").val());
            jQuery("#button-hide-password").show();
            jQuery("#button-show-password").hide();
            jQuery("input[name='pass1-text']").focus();
        });
    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>

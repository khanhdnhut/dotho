<?php
$userBO = json_decode(Session::get("userInfo"));

if (isset($userBO->manage_tags_columns_show)) {
    if (isset($userBO->manage_tags_columns_show->description_show)) {
        $description_show = $userBO->manage_tags_columns_show->description_show;
    } else {
        $description_show = true;
    }
    if (isset($userBO->manage_tags_columns_show->slug_show)) {
        $slug_show = $userBO->manage_tags_columns_show->slug_show;
    } else {
        $slug_show = true;
    }
} else {
    $description_show = true;
    $slug_show = true;
}

if (isset($userBO->tags_per_page)) {
    $tags_per_page = $userBO->tags_per_page;
} else if (isset($_SESSION['options']) && isset($_SESSION['options']->tags_per_page)) {
    $tags_per_page = $_SESSION['options']->tags_per_page;
} else {
    $tags_per_page = TAGS_PER_PAGE_DEFAULT;
}

if (!(isset($this->ajax) && $this->ajax)) {

    ?>

    <div class="metabox-prefs" id="screen-meta">
        <div aria-label="Contextual Help Tab" tabindex="-1" class="hidden" id="contextual-help-wrap">
            Help
        </div>
        <div aria-label="Tùy chọn tìm kiếm Tab" tabindex="-1" class="hidden" id="screen-options-wrap">
            <form method="post" id="adv-settings" >
                <fieldset class="metabox-prefs">
                    <legend><?php echo TITLE_COLUMNS; ?></legend>
                    <label><input type="checkbox" <?php if (isset($description_show) && $description_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="description" name="description_show" class="hide-column-tog"><?php echo TITLE_DESCRIPTION; ?></label>
                    <label><input type="checkbox" <?php if (isset($slug_show) && $slug_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="slug" name="slug_show" class="hide-column-tog"><?php echo TITLE_SLUG; ?></label>
                </fieldset>
                <fieldset class="screen-options">
                    <legend><?php echo TITLE_PAGINATION; ?></legend>
                    <label for="tags_per_page"><?php echo TITLE_NUMBER_OF_ITEMS_PER_PAGE; ?></label>
                    <input type="number" value="<?php
                    if (isset($tags_per_page)) {
                        echo $tags_per_page;
                    } else {
                        echo TAGS_PER_PAGE_DEFAULT;
                    }

                    ?>" maxlength="3" id="tags_per_page" name="tags_per_page" class="screen-per-page" max="999" min="1" step="1">
                </fieldset>
                <input type="hidden" value="adv_setting" name="adv_setting">
                <p class="submit"><input type="submit" value="<?php echo TITLE_APPLY; ?>" class="button button-primary"></p>            
            </form>
        </div>
    </div>
    <div id="screen-meta-links">
        <div class="hide-if-no-js screen-meta-toggle" id="contextual-help-link-wrap">
            <button aria-expanded="false" aria-controls="contextual-help-wrap" class="button show-settings" id="contextual-help-link" type="button">Help</button>
        </div>
        <div class="hide-if-no-js screen-meta-toggle" id="screen-options-link-wrap">
            <button aria-expanded="false" aria-controls="screen-options-wrap" class="button show-settings" id="show-settings-link" type="button">Tùy chọn tìm kiếm</button>
        </div>
    </div>
    <div class="wrap2">
    <?php }

    ?>


    <h1>
        <?php echo TAG_TITLE_TAGS; ?> 
        <a class="page-title-action" onclick="getAddNewPage(this)" ><?php echo TITLE_ADD_NEW; ?></a>
    </h1>

    <?php $this->renderFeedbackMessages(); ?>

    <form id="form-tag-edit" method="post"  onsubmit="submitFormTagEdit(event)">
        <input type="hidden" value="<?php
        if (isset($this->orderby)) {
            echo htmlspecialchars($this->orderby);
        }

        ?>" name="orderby"/>
        <input type="hidden" value="<?php
        if (isset($this->order)) {
            echo htmlspecialchars($this->order);
        }

        ?>" name="order"/>

        <input type="hidden" value="" name="type"/>

        <p class="search-box">
            <label for="tag-search-input" class="screen-reader-text">
                <?php echo TAG_TITLE_SEARCH_TAGS; ?>:</label>
            <input type="search" value="<?php
            if (isset($this->s)) {
                echo htmlspecialchars($this->s);
            }

            ?>" name="s" id="tag-search-input" />
            <input type="submit" value="<?php echo TAG_TITLE_SEARCH_TAGS; ?>" class="button" id="search-submit" />
        </p>

        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <label class="screen-reader-text" for="bulk-action-selector-top"><?php echo TITLE_SELECT_BULK_ACTION; ?></label>
                <select id="bulk-action-selector-top" name="action">
                    <option value="-1">
                        <?php echo TITLE_BULK_ACTIONS; ?>
                    </option>
                    <option value="delete">
                        <?php echo TITLE_DELETE; ?>
                    </option>
                </select>
                <div class="button" onclick="applyAction('action')"><?php echo TITLE_APPLY; ?></div>
            </div>

            <?php if ($this->pageNumber > 0) { ?>
                <h2 class="screen-reader-text"><?php echo USER_TITLE_USERS_LIST_NAVIGATION; ?></h2>
                <div class="tablenav-pages"><span class="displaying-num"><?php echo $this->count; ?> <?php echo TITLE_ITEMS; ?></span>
                    <span class="pagination-links">
                        <?php if ($this->page == 1) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">«</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">‹</span>
                        <?php } else { ?>
                            <a  href="#" page="1" onclick="filterPage(this)" class="first-page">
                                <span class="screen-reader-text"><?php echo TITLE_FIRST_PAGE; ?></span>
                                <span aria-hidden="true">«</span>
                            </a>
                            <a href="#" page="<?php echo ($this->page - 1); ?>" onclick="filterPage(this)" class="prev-page">
                                <span class="screen-reader-text"><?php echo TITLE_PREVIOUS_PAGE; ?></span>
                                <span aria-hidden="true">‹</span>
                            </a>
                        <?php } ?>
                        <span class="paging-input">
                            <label class="screen-reader-text" for="current-page-selector"><?php echo TITLE_CURRENT_PAGE; ?></label>
                            <input type="text" aria-describedby="table-paging" size="1" value="<?php echo $this->page; ?>" name="page" id="current-page-selector" class="current-page"/>
                            <?php echo TITLE_OF; ?> <span class="total-pages"><?php echo $this->pageNumber; ?></span>
                        </span>

                        <?php if ($this->page == $this->pageNumber) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">›</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">»</span>
                        <?php } else { ?>
                            <a href="#" page="<?php echo ($this->page + 1); ?>" onclick="filterPage(this)" class="next-page">
                                <span class="screen-reader-text"><?php echo TITLE_NEXT_PAGE; ?></span>
                                <span aria-hidden="true">›</span>
                            </a>
                            <a href="#" page="<?php echo $this->pageNumber; ?>" onclick="filterPage(this)" class="last-page">
                                <span class="screen-reader-text"><?php echo TITLE_LAST_PAGE; ?></span>
                                <span aria-hidden="true">»</span>
                            </a>
                        <?php } ?> 
                    </span>
                </div>
                <br class="clear">
            <?php } ?>        
        </div>
        <h2 class="screen-reader-text"><?php echo TAG_TITLE_TAGS_LIST; ?></h2>
        <table class="wp-list-table widefat fixed striped tags">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column" id="cb">
                        <label for="cb-select-all-1" class="screen-reader-text"><?php echo TITLE_SELECT_ALL; ?></label>
                        <input type="checkbox" id="cb-select-all-1" onclick="checkAll(this)">
                    </td>

                    <?php
                    if (isset($this->orderby) && $this->orderby == "name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-name sorted <?php echo $this->order; ?>" id="name" scope="col">
                            <a href="#" orderby="name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-name sortable desc" id="name" scope="col">
                            <a href="#" orderby="name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "description" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="description" scope="col">
                            <a href="#" orderby="description" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_DESCRIPTION; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="description" scope="col">
                            <a href="#" orderby="description" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_DESCRIPTION; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "slug" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="slug" scope="col">
                            <a href="#" orderby="slug" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_SLUG; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="slug" scope="col">
                            <a href="#" orderby="slug" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_SLUG; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>
                </tr>
            </thead>

            <tbody data-wp-lists="list:tag" id="the-list">
                <?php
                if (!is_null($this->taxonomyList)) {
                    foreach ($this->taxonomyList as $taxonomyInfo) {

                        ?>
                        <tr id="tag-<?php echo $taxonomyInfo->term_taxonomy_id; ?>">
                            <th class="check-column" scope="row">
                                <label for="tag_<?php echo $taxonomyInfo->term_taxonomy_id; ?>" class="screen-reader-text"><?php echo TITLE_SELECT; ?> <?php echo htmlspecialchars($taxonomyInfo->name); ?></label>
                                <input type="checkbox" value="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" class="author" id="tag_<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="tags[]" >
                            </th>
                            <td data-colname="name" class="name column-name has-row-actions column-primary">                                
                                <strong>
                                    <a href="#" tag="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($taxonomyInfo->name); ?>" onclick="getTagInfoPage(this)"><?php echo htmlspecialchars($taxonomyInfo->name); ?></a>
                                </strong>
                                <br>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="#" tag="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($taxonomyInfo->name); ?>" onclick="getEditTagPage(this)"><?php echo TITLE_EDIT; ?>
                                        </a>
                                    </span>
                                    | <span class="delete">
                                        <a href="#" class="submitdelete" tag="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($taxonomyInfo->name); ?>" onclick="deleteTag(this)"><?php echo TITLE_DELETE; ?>
                                        </a>
                                    </span>
                                </div>
                                <button class="toggle-row" type="button">
                                    <span class="screen-reader-text"><?php echo TITLE_SHOW_MORE_DETAILS; ?></span>
                                </button>
                            </td>

                            <td data-colname="<?php echo USER_TITLE_EMAIL; ?>" class="description column-description <?php
                            if (!(isset($description_show) && $description_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($taxonomyInfo->description); ?></td>

                            <td data-colname="<?php echo USER_TITLE_ROLE; ?>" class="slug column-slug <?php
                            if (!(isset($slug_show) && $slug_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($taxonomyInfo->slug); ?></td>

                        </tr>      
                        <?php
                    }
                }

                ?>
            </tbody>

            <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <label for="cb-select-all-2" class="screen-reader-text"><?php echo TITLE_SELECT_ALL; ?></label>
                        <input type="checkbox" id="cb-select-all-2" onclick="checkAll(this)">
                    </td>
                    <?php
                    if (isset($this->orderby) && $this->orderby == "name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-name sorted <?php echo $this->order; ?>" id="name" scope="col">
                            <a href="#" orderby="name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-name sortable desc" id="name" scope="col">
                            <a href="#" orderby="name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "description" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?>  sorted <?php echo $this->order; ?>" id="description" scope="col">
                            <a href="#" orderby="description" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_DESCRIPTION; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?> sortable desc" id="description" scope="col">
                            <a href="#" orderby="description" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_DESCRIPTION; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "slug" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?>  sorted <?php echo $this->order; ?>" id="slug" scope="col">
                            <a href="#" orderby="slug" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_SLUG; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?> sortable desc" id="slug" scope="col">
                            <a href="#" orderby="slug" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_SLUG; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>

                </tr>
            </tfoot>

        </table>
        <div class="tablenav bottom">

            <div class="alignleft actions bulkactions">
                <label class="screen-reader-text" for="bulk-action-selector-bottom"><?php echo TITLE_SELECT_BULK_ACTION; ?></label>
                <select id="bulk-action-selector-bottom" name="action2">
                    <option value="-1"><?php echo TITLE_BULK_ACTIONS; ?></option>
                    <option value="delete"><?php echo TITLE_DELETE; ?></option>
                </select>
                <div class="button" onclick="applyAction('action2')"><?php echo TITLE_APPLY; ?></div>
            </div>


            <?php if ($this->pageNumber > 0) { ?>
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $this->count; ?> <?php echo TITLE_ITEMS; ?></span>
                    <span class="pagination-links">
                        <?php if ($this->page == 1) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">«</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">‹</span>
                        <?php } else { ?>
                            <a href="#" page="1" onclick="filterPage(this)" class="first-page">
                                <span class="screen-reader-text"><?php echo TITLE_FIRST_PAGE; ?></span>
                                <span aria-hidden="true">«</span>
                            </a>
                            <a href="#" page="<?php echo ($this->page - 1); ?>" onclick="filterPage(this)" class="prev-page">
                                <span class="screen-reader-text"><?php echo TITLE_PREVIOUS_PAGE; ?></span>
                                <span aria-hidden="true">‹</span>
                            </a>                        
                        <?php } ?>
                        <span class="screen-reader-text"><?php echo TITLE_CURRENT_PAGE; ?></span>
                        <span class="paging-input" id="table-paging"><?php echo $this->page; ?> <?php echo TITLE_OF; ?> <span class="total-pages"><?php echo $this->pageNumber; ?></span></span>
                        <?php if ($this->page == $this->pageNumber) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">›</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">»</span>
                        <?php } else { ?>
                            <a href="#" page="<?php echo ($this->page + 1); ?>" onclick="filterPage(this)" class="next-page">
                                <span class="screen-reader-text"><?php echo TITLE_NEXT_PAGE; ?></span>
                                <span aria-hidden="true">›</span>
                            </a>
                            <a href="#" page="<?php echo $this->pageNumber; ?>" onclick="filterPage(this)" class="last-page">
                                <span class="screen-reader-text"><?php echo TITLE_LAST_PAGE; ?></span>
                                <span aria-hidden="true">»</span>
                            </a>                      
                        <?php } ?>
                </div>
                <br class="clear">
                <br class="clear">
            <?php } ?> 
        </div>
    </form>
    <?php if (!(isset($this->ajax) && $this->ajax)) {

        ?>
    </div>
    <?php
}
if (!(isset($this->ajax) && $this->ajax)) {

    ?>
    <script>
        jQuery("#adv-settings").submit(function (e) {
            e.preventDefault(); //STOP default action
            var postData = jQuery(this).serializeArray();
            var postDataSearch = jQuery("#form-tag-edit").serializeArray();
            for (var i = 0; i < postDataSearch.length; i++) {
                if (postDataSearch[i].name == "type") {
                    postDataSearch[i].value == "";
                } else if (postDataSearch[i].name == "action") {
                    postDataSearch[i].value == "-1";
                } else if (postDataSearch[i].name == "action2") {
                    postDataSearch[i].value == "-1";
                }
                postData[postData.length] = postDataSearch[i];
            }
            searchTag(postData);
        });

    //        jQuery("#form-tag-edit").submit(function (e) {
    //            e.preventDefault(); //STOP default action
    //            var postData = jQuery(this).serializeArray();
    //            searchTag(postData);
    //        });



        function submitFormTagEdit(e) {
            e.preventDefault(); //STOP default action
            try {
                var postData = jQuery("#form-tag-edit").serializeArray();
                searchTag(postData);
            } catch (e) {

            }
            return false;
        }

        function searchTag(postData) {
            jQuery.ajax({
                url: "",
                type: "POST",
                data: postData,
                success: function (data, textStatus, jqXHR)
                {
                    jQuery(".wrap2").html(data);
                    //data: return data from server
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    //if fails      
                }
            });
        }

        function filterOrderBy(element) {
            var orderby = jQuery(element).attr("orderby");
            var order = jQuery(element).attr("order");
            if (order == "asc") {
                order = "desc";
            } else {
                order = "asc";
            }
            jQuery('#form-tag-edit input[name="orderby"]').val(orderby);
            jQuery('#form-tag-edit input[name="order"]').val(order);
            var postData = jQuery("#form-tag-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchTag(postData);
        }

        function filterPage(element) {
            var page = jQuery(element).attr("page");
            jQuery('#form-tag-edit input[name="page"]').val(page);
            var postData = jQuery("#form-tag-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchTag(postData);
        }

        function getEditTagPage(element) {
            var term_taxonomy_id = jQuery(element).attr("tag");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . TAG_CP_EDIT; ?>" + term_taxonomy_id + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getAddNewPage(element) {
            var url = "<?php echo URL . TAG_CP_ADD_NEW; ?>";
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getTagInfoPage(element) {
            var tag = jQuery(element).attr("tag");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . TAG_CP_INFO; ?>" + tag + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function deleteTag(element) {
            var tag = jQuery(element).attr("tag");
            var name = jQuery(element).attr("name");
            if (confirm('<?php echo TAG_CONFIRM_DELETE; ?>' + name + '<?php echo CONFIRM_DELETE_CANCEL_OK; ?>')) {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='tags[]'][type=checkbox]").prop('checked', false);
                jQuery("input[name='tags[]'][type=checkbox][value='" + tag + "']").prop('checked', true);

                jQuery('#form-tag-edit select[name="action"] option').removeAttr('selected');
                jQuery('#form-tag-edit select[name="action2"] option').removeAttr('selected');
                jQuery('#form-tag-edit select[name="action"] option[value="delete"]').attr('selected', true);
                jQuery('#form-tag-edit input[name="type"]').val('action');
                var postData = jQuery("#form-tag-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchTag(postData);

            }
        }

        function applyAction(type) {
            if (confirm('<?php echo CATEGORY_CONFIRM_ACTION . CONFIRM_ACTION_CANCEL_OK; ?>')) {
                jQuery('#form-tag-edit input[name="type"]').val(type);
                var postData = jQuery("#form-tag-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchTag(postData);
            }
        }

        function checkAll(element) {
            if (jQuery(element).prop('checked')) {
                jQuery("#cb-select-all-1").prop('checked', true);
                jQuery("#cb-select-all-2").prop('checked', true);
                jQuery("input[name='tags[]'][type=checkbox]").prop('checked', true);
            } else {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='tags[]'][type=checkbox]").prop('checked', false);
            }

        }

    </script>
<?php } ?>
<br class="clear">
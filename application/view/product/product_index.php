<?php
$userBO = json_decode(Session::get("userInfo"));

if (isset($userBO->manage_products_columns_show)) {
    if (isset($userBO->manage_products_columns_show->category_name_show)) {
        $category_name_show = $userBO->manage_products_columns_show->category_name_show;
    } else {
        $category_name_show = true;
    }
} else {
    $category_name_show = true;
}

if (isset($userBO->products_per_page)) {
    $products_per_page = $userBO->products_per_page;
} else if (isset($_SESSION['options']) && isset($_SESSION['options']->products_per_page)) {
    $products_per_page = $_SESSION['options']->products_per_page;
} else {
    $products_per_page = PRODUCTS_PER_PAGE_DEFAULT;
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

                    <label><input type="checkbox" <?php if (isset($category_name_show) && $category_name_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="category" name="category_name_show" class="hide-column-tog"><?php echo PRODUCT_TITLE_CATEGORY; ?></label>
                </fieldset>
                <fieldset class="screen-options">
                    <legend><?php echo TITLE_PAGINATION; ?></legend>
                    <label for="products_per_page"><?php echo TITLE_NUMBER_OF_ITEMS_PER_PAGE; ?></label>
                    <input type="number" value="<?php
                    if (isset($products_per_page)) {
                        echo $products_per_page;
                    } else {
                        echo PRODUCTS_PER_PAGE_DEFAULT;
                    }

                    ?>" maxlength="3" id="products_per_page" name="products_per_page" class="screen-per-page" max="999" min="1" step="1">
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
        <?php echo PRODUCT_TITLE_PRODUCT_1; ?> 
        <a class="page-title-action" onclick="getAddNewPage(this)" ><?php echo TITLE_ADD_NEW; ?></a>
    </h1>

    <?php $this->renderFeedbackMessages(); ?>

    <form id="form-product-edit" method="post" onsubmit="submitFormProductEdit(event)">
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
            <label for="product-search-input" class="screen-reader-text">
                <?php echo TITLE_SEARCH; ?>:</label>
            <input type="search" value="<?php
            if (isset($this->s)) {
                echo htmlspecialchars($this->s);
            }

            ?>" name="s" id="product-search-input" />
            <input type="submit" value="<?php echo TITLE_SEARCH; ?>" class="button" id="search-submit" />
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
        <h2 class="screen-reader-text"><?php echo PRODUCT_TITLE_PRODUCTS_LIST; ?></h2>
        <table class="wp-list-table widefat fixed striped products">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column" id="cb">
                        <label for="cb-select-all-1" class="screen-reader-text"><?php echo TITLE_SELECT_ALL; ?></label>
                        <input type="checkbox" id="cb-select-all-1" onclick="checkAll(this)">
                    </td>

                    <?php
                    if (isset($this->orderby) && $this->orderby == "post_title" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-post_title sorted <?php echo $this->order; ?>" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-post_title sortable desc" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "category_name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-category_name <?php
                        if (!(isset($category_name_show) && $category_name_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="category_name" scope="col">
                            <a href="#" orderby="category_name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo CATEGORY_TITLE_CATEGORY_1; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-category_name <?php
                        if (!(isset($category_name_show) && $category_name_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="category_name" scope="col">
                            <a href="#" orderby="category_name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo CATEGORY_TITLE_CATEGORY_1; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>
                </tr>
            </thead>

            <tbody data-wp-lists="list:product" id="the-list">
                <?php
                if (!is_null($this->productList)) {
                    foreach ($this->productList as $productInfo) {

                        ?>
                        <tr id="product-<?php echo $productInfo->ID; ?>">
                            <th class="check-column" scope="row">
                                <label for="product_<?php echo $productInfo->ID; ?>" class="screen-reader-text"><?php echo TITLE_SELECT; ?> <?php echo htmlspecialchars($productInfo->post_title); ?></label>
                                <input type="checkbox" value="<?php echo $productInfo->ID; ?>" class="author" id="product_<?php echo $productInfo->ID; ?>" name="products[]" >
                            </th>

                            <td data-colname="<?php echo TITLE_NAME; ?>" class="username column-username has-row-actions column-primary">
                                <img width="32" height="32" class="image image-32 photo" srcset="<?php
                                if (isset($productInfo->images) && count($productInfo->images) > 0) {
                                    $image = $productInfo->images[0];
                                    if (isset($image->image_url)) {
                                        echo URL . htmlspecialchars($image->slider_thumb_url);
                                    } else {
                                        echo URL . USER_VALUE_AVATAR_DEFAULT;
                                    }
                                }

                                ?>" src="<?php
                                     if (isset($productInfo->images) && count($productInfo->images) > 0) {
                                         $image = $productInfo->images[0];
                                         if (isset($image->image_url)) {
                                             echo URL . htmlspecialchars($image->slider_thumb_url);
                                         } else {
                                             echo URL . USER_VALUE_AVATAR_DEFAULT;
                                         }
                                     }

                                     ?>" alt=""> 
                                <strong>
                                    <a href="#" product="<?php echo $productInfo->ID; ?>" name="<?php echo htmlspecialchars($productInfo->post_name); ?>" onclick="getProductInfoPage(this)"><?php echo htmlspecialchars($productInfo->post_title); ?></a>
                                </strong>
                                <br>
                                <div class="row-actions">
                                    <span class="view">
                                        <a href="#" product="<?php echo $productInfo->ID; ?>" name="<?php echo htmlspecialchars($productInfo->post_name); ?>" onclick="viewProduct(this)"><?php echo TITLE_VIEW; ?>
                                        </a>
                                    </span> |
                                    <span class="edit">
                                        <a href="#" product="<?php echo $productInfo->ID; ?>" name="<?php echo htmlspecialchars($productInfo->post_name); ?>" onclick="getEditProductPage(this)"><?php echo TITLE_EDIT; ?>
                                        </a>
                                    </span> |
                                    <span class="delete">
                                        <a href="#" class="submitdelete" product="<?php echo $productInfo->ID; ?>" name="<?php echo htmlspecialchars($productInfo->post_title); ?>" onclick="deleteProduct(this)"><?php echo TITLE_DELETE; ?>
                                        </a>
                                    </span>
                                </div>
                                <button class="toggle-row" type="button">
                                    <span class="screen-reader-text"><?php echo TITLE_SHOW_MORE_DETAILS; ?></span>
                                </button>
                            </td>

                            <td data-colname="<?php echo CATEGORY_TITLE_CATEGORY_1; ?>" class="slug column-category <?php
                            if (!(isset($category_name_show) && $category_name_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($productInfo->category_name); ?></td>

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
                    if (isset($this->orderby) && $this->orderby == "post_title" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-post_title sorted <?php echo $this->order; ?>" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-post_title sortable desc" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TITLE_NAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "category_name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-category_name <?php
                        if (!(isset($category_name_show) && $category_name_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="category_name" scope="col">
                            <a href="#" orderby="category_name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo CATEGORY_TITLE_CATEGORY_1; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-category_name <?php
                        if (!(isset($category_name_show) && $category_name_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="category_name" scope="col">
                            <a href="#" orderby="category_name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo CATEGORY_TITLE_CATEGORY_1; ?></span>
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
            var postDataSearch = jQuery("#form-product-edit").serializeArray();
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
            searchProduct(postData);
        });

        //        jQuery("#form-product-edit").submit(function (e) {
        //            e.preventDefault(); //STOP default action
        //            var postData = jQuery(this).serializeArray();
        //            searchProduct(postData);
        //        });

        function submitFormProductEdit(e) {
            e.preventDefault(); //STOP default action
            try {
                var postData = jQuery("#form-product-edit").serializeArray();
                searchProduct(postData);
            } catch (e) {

            }
            return false;
        }

        function searchProduct(postData) {
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
            jQuery('#form-product-edit input[name="orderby"]').val(orderby);
            jQuery('#form-product-edit input[name="order"]').val(order);
            var postData = jQuery("#form-product-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchProduct(postData);
        }

        function filterPage(element) {
            var page = jQuery(element).attr("page");
            jQuery('#form-product-edit input[name="page"]').val(page);
            var postData = jQuery("#form-product-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchProduct(postData);
        }

        function getEditProductPage(element) {
            var ID = jQuery(element).attr("product");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . PRODUCT_CP_EDIT; ?>" + ID + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getAddNewPage(element) {
            var url = "<?php echo URL . PRODUCT_CP_ADD_NEW ?>";
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getProductInfoPage(element) {
            var product = jQuery(element).attr("product");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . PRODUCT_CP_INFO; ?>" + product + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }
        function viewProduct(element) {
            var product = jQuery(element).attr("product");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . PRODUCT_CP_VIEW; ?>" + product + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function deleteProduct(element) {
            var product = jQuery(element).attr("product");
            var name = jQuery(element).attr("name");
            if (confirm('<?php echo PRODUCT_CONFIRM_DELETE_PRODUCT; ?>' + name + '<?php echo CONFIRM_DELETE_CANCEL_OK; ?>')) {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='products[]'][type=checkbox]").prop('checked', false);
                jQuery("input[name='products[]'][type=checkbox][value='" + product + "']").prop('checked', true);

                jQuery('#form-product-edit select[name="action"] option').removeAttr('selected');
                jQuery('#form-product-edit select[name="action2"] option').removeAttr('selected');
                jQuery('#form-product-edit select[name="action"] option[value="delete"]').attr('selected', true);
                jQuery('#form-product-edit input[name="type"]').val('action');
                var postData = jQuery("#form-product-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchProduct(postData);

            }
        }

        function applyAction(type) {
            if (confirm('<?php echo PRODUCT_CONFIRM_ACTION . CONFIRM_ACTION_CANCEL_OK; ?>')) {
                jQuery('#form-product-edit input[name="type"]').val(type);
                var postData = jQuery("#form-product-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchProduct(postData);
            }
        }

        function checkAll(element) {
            if (jQuery(element).prop('checked')) {
                jQuery("#cb-select-all-1").prop('checked', true);
                jQuery("#cb-select-all-2").prop('checked', true);
                jQuery("input[name='products[]'][type=checkbox]").prop('checked', true);
            } else {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='products[]'][type=checkbox]").prop('checked', false);
            }

        }

    </script>
<?php } ?>
<br class="clear">
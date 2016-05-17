<?php
$userBO = json_decode(Session::get("userInfo"));

if (isset($userBO->manage_users_columns_show)) {
    if (isset($userBO->manage_users_columns_show->email_show)) {
        $email_show = $userBO->manage_users_columns_show->email_show;
    } else {
        $email_show = true;
    }
    if (isset($userBO->manage_users_columns_show->role_show)) {
        $role_show = $userBO->manage_users_columns_show->role_show;
    } else {
        $role_show = true;
    }
} else {
    $email_show = true;
    $role_show = true;
}

if (isset($userBO->users_per_page)) {
    $users_per_page = $userBO->users_per_page;
} else if (isset($_SESSION['options']) && isset($_SESSION['options']->users_per_page)) {
    $users_per_page = $_SESSION['options']->users_per_page;
} else {
    $users_per_page = USERS_PER_PAGE_DEFAULT;
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
                    <label><input type="checkbox" <?php if (isset($email_show) && $email_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="email" name="email_show" class="hide-column-tog"><?php echo USER_TITLE_EMAIL; ?></label>
                    <label><input type="checkbox" <?php if (isset($role_show) && $role_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="role" name="role_show" class="hide-column-tog"><?php echo USER_TITLE_ROLE; ?></label>
                </fieldset>
                <fieldset class="screen-options">
                    <legend><?php echo TITLE_PAGINATION; ?></legend>
                    <label for="users_per_page"><?php echo TITLE_NUMBER_OF_ITEMS_PER_PAGE; ?></label>
                    <input type="number" value="<?php
                    if (isset($users_per_page)) {
                        echo $users_per_page;
                    } else {
                        echo USERS_PER_PAGE_DEFAULT;
                    }

                    ?>" maxlength="3" id="users_per_page" name="users_per_page" class="screen-per-page" max="999" min="1" step="1">
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
        <?php echo USER_TITLE_USERS; ?> 
        <a class="page-title-action" onclick="getAddNewPage(this)" ><?php echo TITLE_ADD_NEW; ?></a>
    </h1>

    <?php $this->renderFeedbackMessages(); ?>

    <h2 class="screen-reader-text"><?php echo USER_TITLE_FILTER_USERS_LIST; ?></h2>
    <ul class="subsubsub">
        <li class="all">
            <a class="<?php
            if (isset($this->role) && $this->role == -1) {
                echo "current";
            }

            ?>" href="#" onclick="filterRole(this)" role="-1">
                <?php echo TITLE_ALL; ?> <span class="count">(<?php echo $this->count[TITLE_ALL]; ?>)</span></a> |</li>
        <li class="administrator">
            <a class="<?php
            if (isset($this->role) && $this->role == USER_VALUE_ADMIN) {
                echo "current";
            }

            ?>" href="#" onclick="filterRole(this)" role="<?php echo USER_VALUE_ADMIN; ?>">
                <?php echo USER_TITLE_ADMIN; ?> <span class="count">(<?php echo $this->count[USER_VALUE_ADMIN]; ?>)</span></a> |</li>
        <li class="editor">
            <a class="<?php
            if (isset($this->role) && $this->role == USER_VALUE_EDITOR) {
                echo "current";
            }

            ?>" href="#" onclick="filterRole(this)" role="<?php echo USER_VALUE_EDITOR; ?>">
                <?php echo USER_TITLE_EDITOR; ?> <span class="count">(<?php echo $this->count[USER_VALUE_EDITOR]; ?>)</span></a> |</li>
        <li class="author">
            <a class="<?php
            if (isset($this->role) && $this->role == USER_VALUE_AUTHOR) {
                echo "current";
            }

            ?>" href="#" onclick="filterRole(this)" role="<?php echo USER_VALUE_AUTHOR; ?>">
                <?php echo USER_TITLE_AUTHOR; ?> <span class="count">(<?php echo $this->count[USER_VALUE_AUTHOR]; ?>)</span></a> |</li>
        <li class="contributor">
            <a class="<?php
            if (isset($this->role) && $this->role == USER_VALUE_CONTRIBUTOR) {
                echo "current";
            }

            ?>" href="#" onclick="filterRole(this)" role="<?php echo USER_VALUE_CONTRIBUTOR; ?>">
                <?php echo USER_TITLE_CONTRIBUTOR; ?> <span class="count">(<?php echo $this->count[USER_VALUE_CONTRIBUTOR]; ?>)</span></a> |</li>
        <li class="subscriber">
            <a class="<?php
            if (isset($this->role) && $this->role == USER_VALUE_SUBSCRIBER) {
                echo "current";
            }

            ?>" href="#" onclick="filterRole(this)" role="<?php echo USER_VALUE_SUBSCRIBER; ?>">
                <?php echo USER_TITLE_SUBSCRIBER; ?> <span class="count">(<?php echo $this->count[USER_VALUE_SUBSCRIBER]; ?>)</span></a>
        </li>
    </ul>
    <form id="form-user-edit" method="post"  onsubmit="submitFormUserEdit(event)">
        <input type="hidden" value="<?php
        if (isset($this->role)) {
            echo htmlspecialchars($this->role);
        }

        ?>" name="role"/>
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
            <label for="user-search-input" class="screen-reader-text">
                <?php echo USER_TITLE_SEARCH_USERS; ?>:</label>
            <input type="search" value="<?php
            if (isset($this->s)) {
                echo htmlspecialchars($this->s);
            }

            ?>" name="s" id="user-search-input" />
            <input type="submit" value="<?php echo USER_TITLE_SEARCH_USERS; ?>" class="button" id="search-submit" />
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
            <div class="alignleft actions">
                <label for="new_role" class="screen-reader-text">
                    <?php echo USER_TITLE_CHANGE_ROLE_TO; ?>…</label>
                <select id="new_role" name="new_role">
                    <option value="">
                        <?php echo USER_TITLE_CHANGE_ROLE_TO; ?>…</option>

                    <option value="subscriber">
                        <?php echo USER_TITLE_SUBSCRIBER; ?>
                    </option>
                    <option value="contributor">
                        <?php echo USER_TITLE_CONTRIBUTOR; ?>
                    </option>
                    <option value="author">
                        <?php echo USER_TITLE_AUTHOR; ?>
                    </option>
                    <option value="editor">
                        <?php echo USER_TITLE_EDITOR; ?>
                    </option>
                    <option value="administrator">
                        <?php echo USER_TITLE_ADMIN; ?>
                    </option>
                </select>
                <div class="button" onclick="applyAction('new_role')"><?php echo TITLE_APPLY; ?></div>
            </div>
            <?php if ($this->pageNumber > 0) { ?>
                <h2 class="screen-reader-text"><?php echo USER_TITLE_USERS_LIST_NAVIGATION; ?></h2>
                <div class="tablenav-pages"><span class="displaying-num"><?php echo $this->count[USER_VALUE_NUMBER_SEARCH_USER]; ?> <?php echo TITLE_ITEMS; ?></span>
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
        <h2 class="screen-reader-text"><?php echo USER_TITLE_USERS_LIST; ?></h2>
        <table class="wp-list-table widefat fixed striped users">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column" id="cb">
                        <label for="cb-select-all-1" class="screen-reader-text"><?php echo TITLE_SELECT_ALL; ?></label>
                        <input type="checkbox" id="cb-select-all-1" onclick="checkAll(this)">
                    </td>

                    <?php
                    if (isset($this->orderby) && $this->orderby == "login" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-username column-primary sorted <?php echo $this->order; ?>" id="username" scope="col">
                            <a href="#" orderby="login" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_USERNAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                            <a href="#" orderby="login" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_USERNAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }

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

                    if (isset($this->orderby) && $this->orderby == "email" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-email <?php
                        if (!(isset($email_show) && $email_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="email" scope="col">
                            <a href="#" orderby="email" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_EMAIL; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-email <?php
                        if (!(isset($email_show) && $email_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="email" scope="col">
                            <a href="#" orderby="email" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_EMAIL; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>


                    <th class="manage-column column-role  <?php
                    if (!(isset($role_show) && $role_show)) {
                        echo " hidden";
                    }

                    ?> " id="role" scope="col"><?php echo USER_TITLE_ROLE; ?></th>
                </tr>
            </thead>

            <tbody data-wp-lists="list:user" id="the-list">
                <?php
                if (!is_null($this->userList)) {
                    foreach ($this->userList as $userInfo) {

                        ?>
                        <tr id="user-<?php echo $userInfo->user_id; ?>">
                            <th class="check-column" scope="row">
                                <label for="user_<?php echo $userInfo->user_id; ?>" class="screen-reader-text"><?php echo TITLE_SELECT; ?> <?php echo $userInfo->user_login; ?></label>
                                <input type="checkbox" value="<?php echo $userInfo->user_id; ?>" class="author" id="user_<?php echo $userInfo->user_id; ?>" name="users[]" <?php
                                if ($userInfo->user_id == Session::get('user_id') || $userInfo->user_id != 1) {
                                    echo "";
                                }

                                ?>>
                            </th>
                            <td data-colname="Username" class="username column-username has-row-actions column-primary">
                                <img width="32" height="32" class="avatar avatar-32 photo" srcset="<?php
                                if (isset($userInfo->avatar_url)) {
                                    echo URL . htmlspecialchars($userInfo->avatar_url);
                                } else {
                                    echo URL . USER_VALUE_AVATAR_DEFAULT;
                                }

                                ?>" src="<?php
                                     if (isset($userInfo->avatar_url)) {
                                         echo URL . htmlspecialchars($userInfo->avatar_url);
                                     } else {
                                         echo URL . USER_VALUE_AVATAR_DEFAULT;
                                     }

                                     ?>" alt=""> 
                                <strong>
                                    <a href="#" user="<?php echo $userInfo->user_id; ?>" name="<?php echo htmlspecialchars($userInfo->user_login); ?>" onclick="getUserInfoPage(this)"><?php echo htmlspecialchars($userInfo->user_login); ?></a>
                                </strong>
                                <br>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="#" user="<?php echo $userInfo->user_id; ?>" name="<?php echo htmlspecialchars($userInfo->user_login); ?>" onclick="getEditUserPage(this)"><?php echo TITLE_EDIT; ?>
                                        </a>
                                    </span>
                                    <?php
                                    if ($userInfo->user_id != Session::get('user_id') && $userInfo->user_id != 1) {

                                        ?>
                                        | <span class="delete">
                                            <a href="#" class="submitdelete" user="<?php echo $userInfo->user_id; ?>" name="<?php echo htmlspecialchars($userInfo->user_login); ?>" onclick="deleteUser(this)"><?php echo TITLE_DELETE; ?>
                                            </a>
                                        </span>
                                        <?php
                                    }

                                    ?>
                                </div>
                                <button class="toggle-row" type="button">
                                    <span class="screen-reader-text"><?php echo TITLE_SHOW_MORE_DETAILS; ?></span>
                                </button>
                            </td>
                            <td data-colname="<?php echo TITLE_NAME; ?>" class="name column-name"><?php echo $userInfo->display_name; ?></td>

                            <td data-colname="<?php echo USER_TITLE_EMAIL; ?>" class="email column-email <?php
                            if (!(isset($email_show) && $email_show)) {
                                echo " hidden ";
                            }

                            ?>"><a href="mailto:<?php echo $userInfo->user_email; ?>"><?php echo htmlspecialchars($userInfo->user_email); ?></a></td>
                            <td data-colname="<?php echo USER_TITLE_ROLE; ?>" class="role column-role <?php
                            if (!(isset($role_show) && $role_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($userInfo->wp_capabilities); ?></td>
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
                    if (isset($this->orderby) && $this->orderby == "login" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-username column-primary sorted <?php echo $this->order; ?>" id="username" scope="col">
                            <a href="#" orderby="login" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_USERNAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                            <a href="#" orderby="login" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_USERNAME; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }

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


                    if (isset($this->orderby) && $this->orderby == "email" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-email <?php
                        if (!(isset($email_show) && $email_show)) {
                            echo " hidden";
                        }

                        ?>  sorted <?php echo $this->order; ?>" id="email" scope="col">
                            <a href="#" orderby="email" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_EMAIL; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-email <?php
                        if (!(isset($email_show) && $email_show)) {
                            echo " hidden";
                        }

                        ?> sortable desc" id="email" scope="col">
                            <a href="#" orderby="email" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo USER_TITLE_EMAIL; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>
                    <th class="manage-column column-role <?php
                    if (!(isset($role_show) && $role_show)) {
                        echo " hidden";
                    }

                    ?> " scope = "col"><?php echo USER_TITLE_ROLE;

                    ?></th>
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
            <div class="alignleft actions">
                <label for="new_role2" class="screen-reader-text"><?php echo USER_TITLE_CHANGE_ROLE_TO; ?>…</label>
                <select id="new_role2" name="new_role2">
                    <option value=""><?php echo USER_TITLE_CHANGE_ROLE_TO; ?>…</option>

                    <option value="subscriber"><?php echo USER_TITLE_SUBSCRIBER; ?></option>
                    <option value="contributor"><?php echo USER_TITLE_CONTRIBUTOR; ?></option>
                    <option value="author"><?php echo USER_TITLE_AUTHOR; ?></option>
                    <option value="editor"><?php echo USER_TITLE_EDITOR; ?></option>
                    <option value="administrator"><?php echo USER_TITLE_ADMIN; ?></option>
                </select>            
                <div class="button" onclick="applyAction('new_role2')"><?php echo TITLE_APPLY; ?></div>
            </div>

            <?php if ($this->pageNumber > 0) { ?>
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $this->count[USER_VALUE_NUMBER_SEARCH_USER]; ?> <?php echo TITLE_ITEMS; ?></span>
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
            var postDataSearch = jQuery("#form-user-edit").serializeArray();
            for (var i = 0; i < postDataSearch.length; i++) {
                if (postDataSearch[i].name == "type") {
                    postDataSearch[i].value == "";
                } else if (postDataSearch[i].name == "action") {
                    postDataSearch[i].value == "-1";
                } else if (postDataSearch[i].name == "new_role") {
                    postDataSearch[i].value == "";
                } else if (postDataSearch[i].name == "action2") {
                    postDataSearch[i].value == "-1";
                } else if (postDataSearch[i].name == "new_role2") {
                    postDataSearch[i].value == "";
                }
                postData[postData.length] = postDataSearch[i];
            }
            searchUser(postData);
        });

    //        jQuery("#form-user-edit").submit(function (e) {
    //            e.preventDefault(); //STOP default action
    //            var postData = jQuery(this).serializeArray();
    //            searchUser(postData);
    //        });


        function submitFormUserEdit(e) {
            e.preventDefault(); //STOP default action
            try {
                var postData = jQuery("#form-user-edit").serializeArray();
                searchUser(postData);
            } catch (e) {

            }
            return false;
        }


        function searchUser(postData) {
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

        function filterRole(element) {
            var role = jQuery(element).attr("role");
            jQuery('#form-user-edit input[name="role"]').val(role);
            var postData = jQuery("#form-user-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchUser(postData);
        }

        function filterOrderBy(element) {
            var orderby = jQuery(element).attr("orderby");
            var order = jQuery(element).attr("order");
            if (order == "asc") {
                order = "desc";
            } else {
                order = "asc";
            }
            jQuery('#form-user-edit input[name="orderby"]').val(orderby);
            jQuery('#form-user-edit input[name="order"]').val(order);
            var postData = jQuery("#form-user-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchUser(postData);
        }

        function filterPage(element) {
            var page = jQuery(element).attr("page");
            jQuery('#form-user-edit input[name="page"]').val(page);
            var postData = jQuery("#form-user-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchUser(postData);
        }

        function getEditUserPage(element) {
            var user_id = jQuery(element).attr("user");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . USER_CP_EDIT; ?>" + user_id + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getAddNewPage(element) {
            var url = "<?php echo URL . USER_CP_ADD_NEW; ?>";
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getUserInfoPage(element) {
            var user = jQuery(element).attr("user");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . USER_CP_INFO; ?>" + user + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function deleteUser(element) {
            var user = jQuery(element).attr("user");
            var name = jQuery(element).attr("name");
            if (confirm('<?php echo USER_CONFIRM_DELETE_USER; ?>' + name + '<?php echo CONFIRM_DELETE_CANCEL_OK; ?>')) {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='users[]'][type=checkbox]").prop('checked', false);
                jQuery("input[name='users[]'][type=checkbox][value='" + user + "']").prop('checked', true);

                jQuery('#form-user-edit select[name="action"] option').removeAttr('selected');
                jQuery('#form-user-edit select[name="action2"] option').removeAttr('selected');
                jQuery('#form-user-edit select[name="new_role"] option').removeAttr('selected');
                jQuery('#form-user-edit select[name="new_role2"] option').removeAttr('selected');
                jQuery('#form-user-edit select[name="action"] option[value="delete"]').attr('selected', true);
                jQuery('#form-user-edit input[name="type"]').val('action');
                var postData = jQuery("#form-user-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchUser(postData);

            }
        }

        function applyAction(type) {
            if (confirm('<?php echo CATEGORY_CONFIRM_ACTION . CONFIRM_ACTION_CANCEL_OK; ?>')) {
                jQuery('#form-user-edit input[name="type"]').val(type);
                var postData = jQuery("#form-user-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchUser(postData);
            }
        }

        function checkAll(element) {
            if (jQuery(element).prop('checked')) {
                jQuery("#cb-select-all-1").prop('checked', true);
                jQuery("#cb-select-all-2").prop('checked', true);
                jQuery("input[name='users[]'][type=checkbox]").prop('checked', true);
            } else {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='users[]'][type=checkbox]").prop('checked', false);
            }

        }

    </script>
    <?php
}

?>
<br class="clear">
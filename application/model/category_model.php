<?php
Model::autoloadModel('taxonomy');

class CategoryModel extends TaxonomyModel
{

    public function get($taxonomy_id)
    {
        $categoryBO = parent::get($taxonomy_id);
        if ($categoryBO != null) {

            Model::autoloadModel("post");
            $postModel = new PostModel($this->db);

            if (isset($categoryBO->post_id)) {
                $postBO = $postModel->get($categoryBO->post_id);
            } else {
                $postBOList = $postModel->getPostRelationshipByTaxonomyId($taxonomy_id, "category");
                if (count($postBOList) != 0) {
                    $postBO = $postBOList[0];
                }
            }
            if (isset($postBO)) {
                $categoryBO->postBO = $postBO;

                Model::autoloadModel('tag');
                $tagModel = new TagModel($this->db);
                $tagList = $tagModel->getTaxonomyRelationshipByObjectId($postBO->ID, 'tag');
                if ($tagList != NULL && count($tagList) > 0) {
                    $categoryBO->tag_list = $tagList;
                }

                if (isset($postBO->image_ids)) {
                    $image_ids = json_decode($postBO->image_ids);
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $categoryBO->images = $imageModel->getImagesByArray($image_ids);
                    if ($categoryBO->images != null && is_array($categoryBO->images) && count($categoryBO->images) > 0) {
                        $categoryBO->imagePresentation = $categoryBO->images[0];
                    }
                }
            }
        }
        return $categoryBO;
    }

    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = CATEGORY_ERROR_ADD_NEW;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "category") != FALSE) {
                $_SESSION["fb_error"][] = USER_ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = USER_ERROR_NAME_EMPTY;
            return false;
        }

        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "category")) {
                $_SESSION["fb_error"][] = USER_ERROR_SLUG_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = USER_ERROR_SLUG_EMPTY;
            return false;
        }

        if (!isset($para->parent) || $para->parent == "" || !is_numeric($para->parent)) {
            $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->parent = (int) $para->parent;
            if ($para->parent < 0) {
                $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            }
        }

        if (isset($para->tag_list) && $para->tag_list != NULL && $para->tag_list != "") {
            $tag_array = explode(",", $para->tag_list);
            $para->tag_array = $tag_array;
        }
        return true;
    }

    /**
     * addToDatabase
     *
     * Add new category
     *
     * @param stdClass $para para for add new category
     */
    public function addContent($categoryBO)
    {
        try {
            BO::autoloadBO("post");
            $postBO = new PostBO();

            if (isset($categoryBO->name)) {
                $postBO->post_title = $categoryBO->name;
            }
            $postBO->post_content = "";
            if (isset($categoryBO->name)) {
                $postBO->post_name = Utils::createSlug($categoryBO->name);
            }

            $postBO->post_author = Session::get("user_id");
            $postBO->post_date = date("Y-m-d H:i:s");
            $postBO->post_date_gmt = gmdate("Y-m-d H:i:s");
            $postBO->post_modified = $postBO->post_date;
            $postBO->post_modified_gmt = $postBO->post_date_gmt;
            $postBO->post_parent = 0;
            $postBO->post_status = "publish";
            $postBO->comment_status = "closed";
            $postBO->ping_status = "open";
            $postBO->guid = "";
            $postBO->post_type = "category";


            if (isset($categoryBO->images)) {
                Model::autoloadModel("image");
                $imageModel = new ImageModel($this->db);
                $imageModel->is_create_thumb = true;
                $imageModel->is_slider_thumb = true;
                $imageModel->slider_thumb_height = SIZE_WITH_SLIDER_THUMB;
//                $imageModel->slider_thumb_crop = true;
                $image_array_id = $imageModel->uploadImages("images");

                if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                    $postBO->image_ids = json_encode($image_array_id);
                } else {
                    return null;
                }
            }
            Model::autoloadModel("post");
            $postModel = new PostModel($this->db);
            $post_id = $postModel->addToDatabase($postBO);
            if ($post_id != NULL) {
                if (isset($postBO->image_ids) && $postBO->image_ids != "") {
                    if ($postModel->addMetaInfoToDatabase($post_id, "image_ids", $postBO->image_ids) == NULL) {
                        if (isset($imageModel) && isset($image_array_id)) {
                            foreach ($image_array_id as $image_id) {
                                $imageModel->delete($image_id);
                            }
                        }
                        return null;
                    }
                }

                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);

                if ($taxonomyModel->addRelationshipToDatabase($post_id, $categoryBO->term_taxonomy_id, 0) == NULL) {
                    if (isset($imageModel) && isset($image_array_id)) {
                        foreach ($image_array_id as $image_id) {
                            $imageModel->delete($image_id);
                        }
                    }
                    return null;
                }

                if (isset($categoryBO->tag_array) && count($categoryBO->tag_array) > 0) {
                    Model::autoloadModel('tag');
                    $tagModel = new TagModel($this->db);
                    $tag_id_array = $tagModel->addTagArray($categoryBO->tag_array);
                    for ($i = 0; $i < count($tag_id_array); $i++) {
                        $taxonomyModel->addRelationshipToDatabase($post_id, $tag_id_array[$i]);
                    }
                }

                return $post_id;
            } else {
                if (isset($imageModel) && isset($image_id)) {
                    $imageModel->delete($image_id);
                }
            }
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("category");
                $categoryBO = new CategoryBO();

                if (isset($para->name)) {
                    $categoryBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $categoryBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $categoryBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $categoryBO->parent = $para->parent;

                    if ($para->parent == 0) {
                        $categoryBO->level = 0;
                    } else {
                        $categoryParentBO = $this->get($para->parent);
                        if ($categoryParentBO != NULL && isset($categoryParentBO->level)) {
                            $categoryBO->level = $categoryParentBO->level + 1;
                        } else {
                            $categoryBO->level = 1;
                        }
                    }
                }

                if (isset($para->tag_list)) {
                    $categoryBO->tag_list = $para->tag_list;
                }
                if (isset($para->tag_array)) {
                    $categoryBO->tag_array = $para->tag_array;
                }
                if (isset($para->images)) {
                    $categoryBO->images = $para->images;
                }
                $categoryBO->count = 0;
                $categoryBO->term_group = 0;

                $this->db->beginTransaction();
                $categoryBO->term_taxonomy_id = parent::addToDatabase($categoryBO);

                if ($categoryBO->term_taxonomy_id != NULL) {
                    if (isset($categoryBO->images)) {
                        $post_id = $this->addContent($categoryBO);
                    }
                    $this->db->commit();

                    $categoryBOAdded = parent::get($categoryBO->term_taxonomy_id);
                    if (isset($post_id)) {
                        $this->addMetaInfoToDatabase($categoryBOAdded->term_id, "post_id", $post_id);
                    }
                    if (isset($categoryBO->level)) {
                        $this->addMetaInfoToDatabase($categoryBOAdded->term_id, "level", $categoryBO->level);
                    }
                    $_SESSION["fb_success"][] = CATEGORY_SUCCESS_ADD_CATEGORY;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = CATEGORY_SUCCESS_ADD_CATEGORY;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = CATEGORY_ERROR_ADD_NEW;
        }
        return FALSE;
    }

    /**
     * validateUpdateInfo
     *
     * Validate para for update info of category
     *
     * @param stdClass $para para for update info of category
     */
    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = CATEGORY_ERROR_UPDATE_INFO;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = CATEGORY_ERROR_UPDATE_INFO;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = CATEGORY_ERROR_UPDATE_INFO;
                return false;
            }
        }
        if (!(isset($para->name) && $para->name != "")) {
            $_SESSION["fb_error"][] = USER_ERROR_NAME_EMPTY;
            return false;
        }
        if (!(isset($para->slug) && $para->slug != "")) {
            $_SESSION["fb_error"][] = USER_ERROR_SLUG_EMPTY;
            return false;
        }
        if (!isset($para->parent) || $para->parent == "" || !is_numeric($para->parent)) {
            $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->parent = (int) $para->parent;
            if ($para->parent < 0) {
                $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            }
        }

        if (isset($para->tag_list) && $para->tag_list != NULL && $para->tag_list != "") {
            $tag_array = explode(",", $para->tag_list);
            $para->tag_array = $tag_array;
        }

        if (isset($para->category) && $para->category != "" && !is_numeric($para->category)) {
            $_SESSION["fb_error"][] = CATEGORY_ERROR_CATEGORY_INVALID;
            return false;
        }

        return true;
    }

    public function updateContent($categoryBO)
    {
        if (isset($categoryBO->postBO)) {
            $postBO = $categoryBO->postBO;
            try {
                $sql = "UPDATE " . TABLE_POSTS . " ";
                $set = "SET ";
                $where = " WHERE " . TB_POST_COL_ID . " = :post_id;";

                $para_array = [];
                $para_array[":post_id"] = $postBO->ID;

                if (isset($categoryBO->name)) {
                    $postBO->post_title = $categoryBO->name;
                    $set .= " " . TB_POST_COL_POST_TITLE . " = :post_title,";
                    $para_array[":post_title"] = $postBO->post_title;
                }
                if (isset($categoryBO->name)) {
                    $postBO->post_name = Utils::createSlug($categoryBO->name);
                    $set .= " " . TB_POST_COL_POST_NAME . " = :post_name,";
                    $para_array[":post_name"] = $postBO->post_name;
                }
                $is_change_images = false;

                if (isset($postBO->image_ids)) {
                    $image_ids = json_decode($postBO->image_ids);
                } else {
                    $image_ids = array();
                }

                Model::autoloadModel("image");
                $imageModel = new ImageModel($this->db);
                if (isset($categoryBO->images_upload)) {
                    $is_change_images = true;
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_slider_thumb = true;
//                $imageModel->slider_thumb_crop = true;
                    $image_array_id = $imageModel->uploadImages("images");

                    if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                        $image_ids = array_merge($image_ids, $image_array_id);
                    } else {
                        return null;
                    }
                }

                if (isset($categoryBO->image_delete_list) && $categoryBO->image_delete_list != "" && $categoryBO->image_delete_list != NULL) {
                    $is_change_images = true;
                    $image_delete_array = explode(",", $categoryBO->image_delete_list);
                    if (count($image_delete_array) > 0) {
                        foreach ($image_delete_array as $image_delete_id) {
                            $image_ids = array_diff($image_ids, [$image_delete_id]);
//                            array_slice($image_ids, $image_delete_id, 1);
                        }
                    }
                }

                if (count($para_array) != 0) {
                    $set = substr($set, 0, strlen($set) - 1);
                    $sql .= $set . $where;
                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);

                    Model::autoloadModel("post");
                    $postModel = new PostModel($this->db);

                    if ($is_change_images) {
                        $image_ids_new = array();
                        if ($image_ids != null && count($image_ids) > 0) {
                            foreach ($image_ids as $image_id) {
                                $image_ids_new[] = $image_id;
                            }
                        }

                        $image_ids = json_encode($image_ids_new);
                        if (isset($image_ids) && $image_ids != "") {
                            if (isset($postBO->image_ids)) {
                                if (!$postModel->updateMetaInfoToDatabase($postBO->ID, "image_ids", $image_ids)) {
                                    if (isset($imageModel) && isset($image_array_id)) {
                                        foreach ($image_array_id as $image_id) {
                                            $imageModel->delete($image_id);
                                        }
                                    }
                                    return null;
                                } else { //thanh cong xoa image bi tich bo
                                    if (isset($imageModel) && isset($image_delete_array)) {
                                        foreach ($image_delete_array as $image_id) {
                                            $imageModel->delete($image_id);
                                        }
                                    }
                                }
                            } else {
                                if (!$postModel->addMetaInfoToDatabase($postBO->ID, "image_ids", $image_ids)) {
                                    if (isset($imageModel) && isset($image_array_id)) {
                                        foreach ($image_array_id as $image_id) {
                                            $imageModel->delete($image_id);
                                        }
                                    }
                                    return null;
                                } else { //thanh cong xoa image bi tich bo
                                    if (isset($imageModel) && isset($image_delete_array)) {
                                        foreach ($image_delete_array as $image_id) {
                                            $imageModel->delete($image_id);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    return $postBO->ID;
                }
            } catch (Exception $e) {
                
            }
        } else {
            if (isset($categoryBO->images_upload)) {
                $categoryBO->images = $categoryBO->images_upload;
                $this->addContent($categoryBO);
            }
        }
    }

    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $categoryBO = $this->get($para->term_taxonomy_id);
                if ($categoryBO != NULL) {
                    if (isset($para->name)) {
                        $categoryBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $categoryBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $categoryBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $categoryBO->parent = $para->parent;
                    } else {
                        $categoryBO->parent = 0;
                    }

                    if ($para->parent == 0) {
                        $para->level = 0;
                    } else {
                        $categoryParentBO = $this->get($para->parent);
                        if ($categoryParentBO != NULL && isset($categoryParentBO->level)) {
                            $para->level = $categoryParentBO->level + 1;
                        } else {
                            $para->level = 1;
                        }
                    }

                    if (isset($para->image_delete_list)) {
                        $categoryBO->image_delete_list = $para->image_delete_list;
                    }

                    if (isset($para->images)) {
                        $categoryBO->images_upload = $para->images;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($categoryBO)) {

                        if (isset($para->level)) {
                            if (!isset($categoryBO->level)) {
                                $categoryBO->level = $para->level;
                                $this->addMetaInfoToDatabase($categoryBO->term_id, "level", $categoryBO->level);
                            } else if ($categoryBO->level != $para->level) {
                                $categoryBO->level = $para->level;
                                $this->updateMetaInfoToDatabase($categoryBO->term_id, "level", $categoryBO->level);
                            }
                        }

                        if (isset($categoryBO->images_upload) || isset($categoryBO->image_delete_list)) {
                            $post_id = $this->updateContent($categoryBO);
                            if ($post_id != null && !isset($categoryBO->post_id)) {
                                $this->addMetaInfoToDatabase($categoryBO->term_id, "post_id", $post_id);
                            }
                            if (isset($para->tag_array) || isset($categoryBO->tag_list)) {
                                Model::autoloadModel('tag');
                                $tagModel = new TagModel($this->db);
                                Model::autoloadModel('taxonomy');
                                $taxonomyModel = new TaxonomyModel($this->db);
                                if (!isset($para->tag_array) || count($para->tag_array) == 0) {
                                    foreach ($categoryBO->tag_list as $tag) {
                                        $tagModel->deleteRelationship($categoryBO->postBO->ID, $tag->term_taxonomy_id);
                                    }
                                } elseif (!isset($categoryBO->tag_list) || count($categoryBO->tag_list) == 0) {
                                    if (count($para->tag_array) > 0) {
                                        $tag_id_array = $tagModel->addTagArray($para->tag_array);
                                        for ($i = 0; $i < count($tag_id_array); $i++) {
                                            $taxonomyModel->addRelationshipToDatabase($categoryBO->postBO->ID, $tag_id_array[$i]);
                                        }
                                    }
                                } elseif (isset($para->tag_array) && isset($categoryBO->tag_list) &&
                                    count($para->tag_array) > 0 && count($categoryBO->tag_list) > 0) {
                                    $tags_old_array = array();
                                    foreach ($categoryBO->tag_list as $tag_old) {
                                        $tags_old_array[] = $tag_old->name;
                                    }

                                    $tags_new_array = array();
                                    for ($i = 0; $i < count($para->tag_array); $i++) {
                                        if (!in_array($para->tag_array[$i], $tags_old_array)) {
                                            $tags_new_array[] = $para->tag_array[$i];
                                        }
                                    }
                                    if (count($tags_new_array) > 0) {
                                        $tag_id_new_array = $tagModel->addTagArray($tags_new_array);
                                        for ($i = 0; $i < count($tag_id_new_array); $i++) {
                                            $taxonomyModel->addRelationshipToDatabase($categoryBO->postBO->ID, $tag_id_new_array[$i]);
                                        }
                                    }

                                    $tags_delete_array = array();
                                    for ($i = 0; $i < count($categoryBO->tag_list); $i++) {
                                        if (!in_array($categoryBO->tag_list[$i]->name, $para->tag_array)) {
                                            $tags_delete_array[] = $categoryBO->tag_list[$i];
                                        }
                                    }
                                    if (count($tags_delete_array) > 0) {
                                        foreach ($tags_delete_array as $tag) {
                                            $tagModel->deleteRelationship($categoryBO->postBO->ID, $tag->term_taxonomy_id);
                                        }
                                    }
                                }
                            }
                        }

                        $this->db->commit();
                        $_SESSION["fb_success"][] = CATEGORY_SUCCESS_UPDATE_CATEGORY;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = CATEGORY_ERROR_UPDATE_INFO;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = CATEGORY_ERROR_UPDATE_INFO;
        }
        return FALSE;
    }

    /**
     * updateCategoriesPerPages
     *
     * Update number categories per page
     *
     * @param string $categories_per_page number categories per page
     */
    public function updateCategoriesPerPages($categories_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "categories_per_page";
        $meta_value = $categories_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $level_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_categories_columns_show";
        $meta_value = new stdClass();
        $meta_value->description_show = $description_show;
        $meta_value->slug_show = $slug_show;
        $meta_value->level_show = $level_show;
        $meta_value = json_encode($meta_value);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function changeAdvSetting($para)
    {
        $action = NULL;
        if (isset($para->categories_per_page) && is_numeric($para->categories_per_page)) {
            $this->updateCategoriesPerPages($para->categories_per_page);
        }
        $description_show = false;
        $slug_show = false;
        $level_show = false;
        if (isset($para->description_show) && $para->description_show == "description") {
            $description_show = true;
        }
        if (isset($para->slug_show) && $para->slug_show == "slug") {
            $slug_show = true;
        }
        if (isset($para->level_show) && $para->level_show == "level") {
            $level_show = true;
        }
        $this->updateColumnsShow($description_show, $slug_show, $level_show);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userBO = $userModel->get(Session::get("user_id"));
        $userModel->setNewSessionUser($userBO);
    }

    public function executeActionDelete($para)
    {
        if (isset($para->categories) && is_array($para->categories)) {
            foreach ($para->categories as $term_taxonomy_id) {
                $this->delete($term_taxonomy_id);
            }
        }
    }

    public function executeAction($para)
    {
        $action = NULL;
        if (isset($para->type)) {
            if ($para->type == "action") {
                if (isset($para->action) && in_array($para->action, array("delete"))) {
                    $action = $para->action;
                }
            } elseif ($para->type == "action2") {
                if (isset($para->action2) && in_array($para->action2, array("delete"))) {
                    $action = $para->action2;
                }
            }
        }
        if (!is_null($action)) {
            switch ($action) {
                case "delete":
                    $this->executeActionDelete($para);
                    break;
            }
        }
    }

    public function search($view, $para)
    {
        $categories_per_page = CATEGORIES_PER_PAGE_DEFAULT;
        $taxonomy = "category";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->categories_per_page) && is_numeric($userLoginBO->categories_per_page)) {
                $categories_per_page = (int) $userLoginBO->categories_per_page;
            }
        }

        if (!isset($categories_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->categories_per_page = CATEGORIES_PER_PAGE_DEFAULT;
                $categories_per_page = CATEGORIES_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->categories_per_page)) {
                $_SESSION['options']->categories_per_page = CATEGORIES_PER_PAGE_DEFAULT;
                $categories_per_page = CATEGORIES_PER_PAGE_DEFAULT;
            }
        }

        $view->taxonomies_per_page = $categories_per_page;
        $view->taxonomy = $taxonomy;

        parent::search($view, $para);
    }

    public function view($view, $para, $page, $number_results_per_page)
    {
        $view->categoryBO = $this->get($para->term_taxonomy_id);
        if ($view->categoryBO != null) {
            Model::autoloadModel('product');
            $model = new ProductModel($this->db);

            //Lấy danh sách category cha

            $view->categoryList = $this->getAll("category");
            $view->categoryArray = array();
            $view->categoryArray[] = $view->categoryBO;

            while ($view->categoryArray[(count($view->categoryArray) - 1)]->parent != '0') {
                $id = $view->categoryArray[(count($view->categoryArray) - 1)]->parent;
                $category = $this->getCategoryByIDFromArray($id, $view->categoryList);
                if ($category != null) {
                    $view->categoryArray[] = $category;
                } else {
                    break;
                }
            }

            //Lấy danh sách category con     
            $term_taxonomy_id_array = array();
            $post_id_array = array();

            $view->categoryChildArray = $this->getCategoryByParentFromArray($view->categoryBO->term_taxonomy_id, $view->categoryList);
            $view->categoryChildArray[] = $view->categoryBO;
            foreach ($view->categoryChildArray as $categoryBO) {
                $term_taxonomy_id_array[] = $categoryBO->term_taxonomy_id;
                if (isset($categoryBO->post_id)) {
                    $post_id_array[] = $categoryBO->post_id;
                }
            }

            $view->productBOArray = array();

            $countPost = $model->countPostRelationshipByTaxonomyIdArray($term_taxonomy_id_array, $post_id_array, "category");

            if ($countPost > 0) {
                $view->number_page = floor($countPost / $number_results_per_page);
                if ($countPost % $number_results_per_page != 0) {
                    $view->number_page++;
                }
                if (isset($view->page) && is_numeric($view->page) && $view->page > $view->number_page) {
                    $view->page = $view->number_page;
                    $page = $view->number_page;
                } elseif (!isset($view->page)) {
                    $view->page = 1;
                    $page = 1;
                }

                $productBOArray = $model->getPostRelationshipByTaxonomyIdArray($term_taxonomy_id_array, $post_id_array, "category", $page, $number_results_per_page);
//            $productBOArray = $model->getPostRelationshipByTaxonomyId($view->categoryBO->term_taxonomy_id, "category");

                if ($productBOArray != null && is_array($productBOArray) &&
                    count($productBOArray) > 0) {
                    for ($i = 0; $i < count($productBOArray); $i++) {
                        $productBO = $productBOArray[$i];
                        if (isset($post_id_array) && !in_array($productBO->ID, $post_id_array) && $productBO->post_content != "") {
                            $view->productBOArray[] = $productBO;
                        } else if (!isset($post_id_array) && $productBO->post_content != "") {
                            $view->productBOArray[] = $productBO;
                        }
                    }

                    for ($i = 0; $i < count($view->productBOArray); $i++) {
                        $productBO = $view->productBOArray[$i];
                        if (isset($productBO->image_ids)) {
                            $image_ids = json_decode($productBO->image_ids);
                            Model::autoloadModel('image');
                            $imageModel = new ImageModel($this->db);
                            $productBO->images = $imageModel->getImagesByArray($image_ids);
                            if ($productBO->images != null && is_array($productBO->images) && count($productBO->images) > 0) {
                                $productBO->imagePresentation = $productBO->images[0];
                            }
                        }

                        $view->productBOArray[$i] = $productBO;
                    }
                }
            } else {
                $view->number_page = 0;
            }
        }
    }

    public function getCategoryByIDFromArray($category_id, $category_array)
    {
        if ($category_array != null && is_array($category_array) && count($category_array) > 0) {
            foreach ($category_array as $categoryBO) {
                if ($categoryBO != null && isset($categoryBO->term_taxonomy_id) &&
                    $categoryBO->term_taxonomy_id == $category_id) {
                    return $categoryBO;
                }
            }
        }
        return null;
    }

    public function getCategoryByParentFromArray($parent, $category_array)
    {
        $array = array();

        if ($category_array != null && is_array($category_array) && count($category_array) > 0) {
            foreach ($category_array as $categoryBO) {
                if ($categoryBO != null && isset($categoryBO->parent) &&
                    $categoryBO->parent == $parent) {
                    $array[] = $categoryBO;
                    $result = $this->getCategoryByParentFromArray($categoryBO->term_taxonomy_id, $category_array);
                    if ($result != null && is_array($result) && count($result) > 0) {
                        foreach ($result as $a) {
                            $array[] = $a;
                        }
                    }
                }
            }
        }
        return $array;
    }
}

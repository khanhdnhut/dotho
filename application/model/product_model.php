<?php
Model::autoloadModel('post');

class ProductModel extends PostModel
{

    /**
     * validateAddNew
     *
     * Validate para for add new product
     *
     * @param stdClass $para para for add new product
     */
    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_ADD_NEW;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_TITLE_EMPTY;
            return false;
        }

        $post_name = Utils::createSlug($para->post_title);

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        if (!(isset($para->category_id) && $para->category_id != "0")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_CATEGORY_INVALID;
            return false;
        } else {
            Model::autoloadModel("category");
            $categoryModel = new CategoryModel($this->db);
            $categoryBO = $categoryModel->get($para->category_id);
            if ($categoryBO == NULL || !(isset($categoryBO->taxonomy) && $categoryBO->taxonomy == "category")) {
                $_SESSION["fb_error"][] = PRODUCT_ERROR_CATEGORY_INVALID;
                return false;
            }
        }
        if (!(isset($para->post_content) && $para->post_content != "")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_CONTENT_EMPTY;
            return false;
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
     * Add new product
     *
     * @param stdClass $para para for add new product
     */
    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("product");
                $productBO = new ProductBO();

                if (isset($para->post_title)) {
                    $productBO->post_title = $para->post_title;
                }
                if (isset($para->tag_list)) {
                    $productBO->tag_list = $para->tag_list;
                }
                if (isset($para->post_content)) {
                    $productBO->post_content = $para->post_content;
                }
                if (isset($para->post_name)) {
                    $productBO->post_name = $para->post_name;
                }
                if (isset($para->category_id)) {
                    $productBO->category_id = $para->category_id;
                }

                $productBO->post_author = Session::get("user_id");
                $productBO->post_date = date("Y-m-d H:i:s");
                $productBO->post_date_gmt = gmdate("Y-m-d H:i:s");
                $productBO->post_modified = $productBO->post_date;
                $productBO->post_modified_gmt = $productBO->post_date_gmt;
                $productBO->post_parent = 0;
                $productBO->post_status = "publish";
                $productBO->comment_status = "closed";
                $productBO->ping_status = "open";
                $productBO->guid = "";

                $this->db->beginTransaction();
                if (isset($para->images)) {
                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_slider_thumb = true;
                    $imageModel->is_large = true;
//                $imageModel->slider_thumb_crop = true;
                    $image_array_id = $imageModel->uploadImages("images");

                    if (!is_null($image_array_id) && sizeof($image_array_id) != 0) {
                        $productBO->image_ids = json_encode($image_array_id);
                    } else {
                        $_SESSION["fb_error"][] = PRODUCT_ERROR_ADD_NEW;
                        $this->db->rollBack();
                        return FALSE;
                    }
                }

                $post_id = parent::addToDatabase($productBO);
                if ($post_id != NULL) {
                    $guid = PRODUCT_CP_VIEW . $post_id . "/" . $productBO->post_name . "/";
                    if (!$this->updateGuid($post_id, $guid)) {
                        if (isset($imageModel) && isset($image_array_id)) {
                            foreach ($image_array_id as $image_id) {
                                $imageModel->delete($image_id);
                            }
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = PRODUCT_ERROR_ADD_NEW;
                        return FALSE;
                    }

                    if (isset($productBO->image_ids) && $productBO->image_ids != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "image_ids", $productBO->image_ids) == NULL) {
                            if (isset($imageModel) && isset($image_array_id)) {
                                foreach ($image_array_id as $image_id) {
                                    $imageModel->delete($image_id);
                                }
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = PRODUCT_ERROR_ADD_NEW;
                            return FALSE;
                        }
                    }

                    Model::autoloadModel('taxonomy');
                    $taxonomyModel = new TaxonomyModel($this->db);

                    if ($taxonomyModel->addRelationshipToDatabase($post_id, $productBO->category_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_array_id)) {
                            foreach ($image_array_id as $image_id) {
                                $imageModel->delete($image_id);
                            }
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = PRODUCT_ERROR_ADD_NEW;
                        return FALSE;
                    }

                    if (isset($para->tag_array) && is_array($para->tag_array) && count($para->tag_array) > 0) {
                        Model::autoloadModel('tag');
                        $tagModel = new TagModel($this->db);
                        $tag_id_array = $tagModel->addTagArray($para->tag_array);
                        for ($i = 0; $i < count($tag_id_array); $i++) {
                            $taxonomyModel->addRelationshipToDatabase($post_id, $tag_id_array[$i]);
                        }
                    }


                    $this->db->commit();
                    $_SESSION["fb_success"][] = PRODUCT_SUCCESS_ADD_PRODUCT;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = PRODUCT_ERROR_ADD_NEW;
                    if (isset($imageModel) && isset($image_array_id)) {
                        foreach ($image_array_id as $image_id) {
                            $imageModel->delete($image_id);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_ADD_NEW;
        }
        return FALSE;
    }

    /**
     * validateUpdateInfo
     *
     * Validate para for update info of product
     *
     * @param stdClass $para para for update info of product
     */
    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_TITLE_EMPTY;
            return false;
        }

        $post_name = Utils::createSlug($para->post_title);

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        if (!(isset($para->category_id) && $para->category_id != "0")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_CATEGORY_INVALID;
            return false;
        } else {
            Model::autoloadModel("category");
            $categoryModel = new CategoryModel($this->db);
            $categoryBO = $categoryModel->get($para->category_id);
            if ($categoryBO == NULL || !(isset($categoryBO->taxonomy) && $categoryBO->taxonomy == "category")) {
                $_SESSION["fb_error"][] = PRODUCT_ERROR_CATEGORY_INVALID;
                return false;
            }
        }

        if (!(isset($para->post_content) && $para->post_content != "")) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_CONTENT_EMPTY;
            return false;
        }

        if (isset($para->tag_list) && $para->tag_list != NULL && $para->tag_list != "") {
            $tag_array = explode(",", $para->tag_list);
            $para->tag_array = $tag_array;
        }

        return true;
    }

    public function get($post_id)
    {
        try {
            $productBO = parent::get($post_id);
            if ($productBO != NULL) {
                Model::autoloadModel("taxonomy");
                $taxonomyModel = new TaxonomyModel($this->db);

                $categoryList = $taxonomyModel->getTaxonomyRelationshipByObjectId($post_id, "category");
                if (count($categoryList) > 0) {
                    $categoryBO = $categoryList[0];
                    $productBO->category_name = $categoryBO->name;
                    $productBO->category_id = $categoryBO->term_taxonomy_id;
                }

                Model::autoloadModel('tag');
                $tagModel = new TagModel($this->db);
                $tagList = $tagModel->getTaxonomyRelationshipByObjectId($post_id, 'tag');
                if ($tagList != NULL && count($tagList) > 0) {
                    $productBO->tag_list = $tagList;
                }

                if (isset($productBO->image_ids)) {
                    $image_ids = json_decode($productBO->image_ids);
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $productBO->images = $imageModel->getImagesByArray($image_ids);
                    if ($productBO->images != null && is_array($productBO->images) && count($productBO->images) > 0) {
                        $productBO->imagePresentation = $productBO->images[0];
                    }
                }

                return $productBO;
            } else {
                return NULL;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    /**
     * updateInfo
     *
     * Update info of product
     *
     * @param stdClass $para para for update info of product
     */
    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $productBO = $this->get($para->post_id);
                if ($productBO != NULL) {
                    if (isset($para->post_title) && $productBO->post_title != $para->post_title) {
                        $productBO->post_title = $para->post_title;
                    }

                    if (isset($para->post_content) && $productBO->post_content != $para->post_content) {
                        $productBO->post_content = $para->post_content;
                    }
                    if (isset($para->post_name) && $productBO->post_name != $para->post_name) {
                        $productBO->post_name = $para->post_name;
                    }

                    $productBO->post_modified = date("Y-m-d H:i:s");
                    $productBO->post_modified_gmt = gmdate("Y-m-d H:i:s");

                    $this->db->beginTransaction();

                    $is_change_images = false;

                    if (isset($productBO->image_ids)) {
                        $image_ids = json_decode($productBO->image_ids);
                        if ($image_ids == null || !is_array($image_ids) || count($image_ids) <= 0) {
                            $image_ids = array();
                        }
                    } else {
                        $image_ids = array();
                    }

                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    if (isset($para->images)) {
                        $is_change_images = true;
                        $imageModel->is_create_thumb = true;
                        $imageModel->is_slider_thumb = true;
                        $imageModel->is_large = true;
//                $imageModel->slider_thumb_crop = true;
                        $image_array_id = $imageModel->uploadImages("images");

                        if (!is_null($image_array_id) && sizeof($image_array_id) != 0) {
                            $image_ids = array_merge($image_ids, $image_array_id);
                        } else {
                            $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
                            $this->db->rollBack();
                            return FALSE;
                        }
                    }

                    if (isset($para->image_delete_list) && $para->image_delete_list != "" && $para->image_delete_list != NULL) {
                        $is_change_images = true;
                        $image_delete_array = explode(",", $para->image_delete_list);
                        if (count($image_delete_array) > 0) {
                            foreach ($image_delete_array as $image_delete_id) {
                                $image_ids = array_diff($image_ids, [$image_delete_id]);
//                            array_slice($image_ids, $image_delete_id, 1);
                            }
                        }
                    }

                    if ($this->update($productBO)) {

                        $guid = PRODUCT_CP_VIEW . $para->post_id . "/" . $productBO->post_name . "/";

                        if ((isset($productBO->guid) && $productBO->guid != $guid) || !isset($productBO->guid)) {
                            $productBO->guid = $guid;
                            if (!$this->updateGuid($para->post_id, $guid)) {
                                $this->db->rollBack();
                                if (isset($imageModel) && isset($image_ids)) {
                                    $imageModel->delete($image_ids);
                                }
                                $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
                                return FALSE;
                            }
                        }

                        if ($is_change_images) {
                            $image_ids_new = array();
                            if ($image_ids != null && count($image_ids) > 0) {
                                foreach ($image_ids as $image_id) {
                                    $image_ids_new[] = $image_id;
                                }
                            }

                            $image_ids = json_encode($image_ids_new);
                            if (isset($image_ids) && $image_ids != "") {
                                if (isset($productBO->image_ids)) {
                                    if (!$this->updateMetaInfoToDatabase($productBO->ID, "image_ids", $image_ids)) {
                                        $this->db->rollBack();
                                        if (isset($imageModel) && isset($image_array_id)) {
                                            foreach ($image_array_id as $image_id) {
                                                $imageModel->delete($image_id);
                                            }
                                        }
                                        $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
                                        return FALSE;
                                    } else { //thanh cong xoa image bi tich bo
                                        if (isset($imageModel) && isset($image_delete_array)) {
                                            foreach ($image_delete_array as $image_id) {
                                                $imageModel->delete($image_id);
                                            }
                                        }
                                    }
                                } else {
                                    if (!$this->addMetaInfoToDatabase($productBO->ID, "image_ids", $image_ids)) {
                                        $this->db->rollBack();
                                        if (isset($imageModel) && isset($image_array_id)) {
                                            foreach ($image_array_id as $image_id) {
                                                $imageModel->delete($image_id);
                                            }
                                        }
                                        $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
                                        return FALSE;
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



                        if (isset($para->category_id)) {

                            Model::autoloadModel('taxonomy');
                            $taxonomyModel = new TaxonomyModel($this->db);

                            if (!isset($productBO->category_id)) {
                                $productBO->category_id = $para->category_id;
                                if ($taxonomyModel->addRelationshipToDatabase($para->post_id, $productBO->category_id, 0) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_array_id)) {
                                        foreach ($image_array_id as $image_id) {
                                            $imageModel->delete($image_id);
                                        }
                                    }
                                    $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
                                    return FALSE;
                                }
                            } else if ($productBO->category_id != $para->category_id) {
                                if (!$this->updateRelationship($para->post_id, $productBO->category_id, $para->category_id, 0)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_array_id)) {
                                        foreach ($image_array_id as $image_id) {
                                            $imageModel->delete($image_id);
                                        }
                                    }
                                    $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
                                    return FALSE;
                                }
                                $productBO->category_id = $para->category_id;
                            }
                        }

                        if (isset($para->tag_array) || isset($productBO->tag_list)) {
                            Model::autoloadModel('tag');
                            $tagModel = new TagModel($this->db);
                            Model::autoloadModel('taxonomy');
                            $taxonomyModel = new TaxonomyModel($this->db);
                            if (!isset($para->tag_array) || count($para->tag_array) == 0) {
                                foreach ($productBO->tag_list as $tag) {
                                    $tagModel->deleteRelationship($para->post_id, $tag->term_taxonomy_id);
                                }
                            } elseif (!isset($productBO->tag_list) || count($productBO->tag_list) == 0) {
                                if (count($para->tag_array) > 0) {
                                    $tag_id_array = $tagModel->addTagArray($para->tag_array);
                                    for ($i = 0; $i < count($tag_id_array); $i++) {
                                        $taxonomyModel->addRelationshipToDatabase($para->post_id, $tag_id_array[$i]);
                                    }
                                }
                            } elseif (isset($para->tag_array) && isset($productBO->tag_list) &&
                                count($para->tag_array) > 0 && count($productBO->tag_list) > 0) {
                                $tags_old_array = array();
                                foreach ($productBO->tag_list as $tag_old) {
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
                                        $taxonomyModel->addRelationshipToDatabase($para->post_id, $tag_id_new_array[$i]);
                                    }
                                }

                                $tags_delete_array = array();
                                for ($i = 0; $i < count($productBO->tag_list); $i++) {
                                    if (!in_array($productBO->tag_list[$i]->name, $para->tag_array)) {
                                        $tags_delete_array[] = $productBO->tag_list[$i];
                                    }
                                }
                                if (count($tags_delete_array) > 0) {
                                    foreach ($tags_delete_array as $tag) {
                                        $tagModel->deleteRelationship($para->post_id, $tag->term_taxonomy_id);
                                    }
                                }
                            }
                        }

                        $this->db->commit();
                        //thanh cong xoa image bi tich bo
                        if (isset($imageModel) && isset($image_delete_array)) {
                            foreach ($image_delete_array as $image_id) {
                                $imageModel->delete($image_id);
                            }
                        }
                        $_SESSION["fb_success"][] = PRODUCT_SUCCESS_UPDATE_PRODUCT;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        if (isset($imageModel) && isset($image_array_id)) {
                            foreach ($image_array_id as $image_id) {
                                $imageModel->delete($image_id);
                            }
                        }
                        $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
                        return FALSE;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = PRODUCT_ERROR_UPDATE_INFO;
        }
        return FALSE;
    }

    /**
     * updateProductsPerPages
     *
     * Update number products per page
     *
     * @param string $products_per_page number products per page
     */
    public function updateProductsPerPages($products_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "products_per_page";
        $meta_value = $products_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_products_columns_show";
        $meta_value = new stdClass();
        $meta_value->description_show = $description_show;
        $meta_value->slug_show = $slug_show;
        $meta_value->tours_show = $tours_show;
        $meta_value = json_encode($meta_value);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function changeAdvSetting($para)
    {
        $action = NULL;
        if (isset($para->products_per_page) && is_numeric($para->products_per_page)) {
            $this->updateProductsPerPages($para->products_per_page);
        }
        $description_show = false;
        $slug_show = false;
        $tours_show = false;
        if (isset($para->description_show) && $para->description_show == "description") {
            $description_show = true;
        }
        if (isset($para->slug_show) && $para->slug_show == "slug") {
            $slug_show = true;
        }
        if (isset($para->tours_show) && $para->tours_show == "tours") {
            $tours_show = true;
        }
        $this->updateColumnsShow($description_show, $slug_show, $tours_show);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userBO = $userModel->get(Session::get("user_id"));
        $userModel->setNewSessionUser($userBO);
    }

    public function executeActionDelete($para)
    {
        if (isset($para->products) && is_array($para->products)) {
            foreach ($para->products as $post_id) {
                $this->delete($post_id);
            }
        }
    }

    public function delete($post_id)
    {
        try {
            $productBO = $this->get($post_id);
            if ($productBO != NULL) {
                if (parent::delete($post_id)) {
                    if (isset($productBO->category_id)) {
                        $this->deleteRelationship($post_id, $productBO->category_id);
                    }
                    if (isset($productBO->image_ids)) {
                        $image_ids = json_decode($productBO->image_ids);
                        if (is_array($image_ids) && count($image_ids) > 0) {
                            Model::autoloadModel('image');
                            $imageModel = new ImageModel($this->db);
                            foreach ($image_ids as $image_id) {
                                $imageModel->delete($image_id);
                            }
                        }
                    }
                    return TRUE;
                }
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
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
        $products_per_page = PRODUCTS_PER_PAGE_DEFAULT;
        $product = "product";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->products_per_page) && is_numeric($userLoginBO->products_per_page)) {
                $products_per_page = (int) $userLoginBO->products_per_page;
            }
        }

        if (!isset($products_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->products_per_page = PRODUCTS_PER_PAGE_DEFAULT;
                $products_per_page = PRODUCTS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->products_per_page)) {
                $_SESSION['options']->products_per_page = PRODUCTS_PER_PAGE_DEFAULT;
                $products_per_page = PRODUCTS_PER_PAGE_DEFAULT;
            }
        }

        $view->products_per_page = $products_per_page;
        $view->product = $product;

        try {
            $paraSQL = [];
            $sqlSelectAll = "SELECT DISTINCT 
                                im.`meta_value` AS image_ids,
                                ts.`name` AS category_name,
                                tc.`term_taxonomy_id` AS category_id,
                                po.*  ";
            $sqlSelectCount = "SELECT COUNT(*) as countProduct ";
            //para: orderby, order, page, s, paged, countries, new_role, new_role2, action, action2
            $sqlFrom = " FROM
                            posts AS po
                            LEFT JOIN `postmeta` AS im ON po.`ID` = im.`post_id` AND im.`meta_key` = 'image_ids'                                     LEFT JOIN `term_relationships` AS rd ON po.`ID` = rd.`object_id` 
                            JOIN `term_taxonomy` AS tc ON rd.`term_taxonomy_id` = tc.`term_taxonomy_id` AND tc.`taxonomy` = 'category' 
                            LEFT JOIN `terms` AS ts ON tc.`term_id` = ts.`term_id` ";
            $sqlWhere = " WHERE po.`post_type` = 'product' ";


            if (isset($para->s) && strlen(trim($para->s)) > 0) {
                $sqlWhere .= " AND (
                                po.`post_content` LIKE :s 
                                OR po.`post_name` LIKE :s 
                                OR po.`post_title` LIKE :s 
                                OR ts.`name` LIKE :s
                              ) ";
                $paraSQL[':s'] = "%" . $para->s . "%";
                $view->s = $para->s;
            }

            $view->orderby = "post_title";
            $view->order = "asc";

            if (isset($para->orderby) && in_array($para->orderby, array("post_title", "category_name"))) {
                switch ($para->orderby) {
                    case "post_title":
                        $para->orderby = "post_title";
                        $view->orderby = "post_title";
                        break;
                    case "category_name":
                        $para->orderby = "category_name";
                        $view->orderby = "category_name";
                        break;
                }

                if (isset($para->order) && in_array($para->order, array("desc", "asc"))) {
                    $view->order = $para->order;
                } else {
                    $para->order = "asc";
                    $view->order = "asc";
                }
                $sqlOrderby = " ORDER BY " . $para->orderby . " " . $para->order;
            } else {
                $sqlOrderby = " ORDER BY " . TB_POST_COL_POST_TITLE . " ASC";
            }

            $sqlCount = $sqlSelectCount . $sqlFrom . $sqlWhere;
            $sth = $this->db->prepare($sqlCount);
            $sth->execute($paraSQL);
            $countProduct = (int) $sth->fetch()->countProduct;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countProduct > 0) {
                $view->count = $countProduct;

                $view->pageNumber = floor($view->count / $view->products_per_page);
                if ($view->count % $view->products_per_page != 0) {
                    $view->pageNumber++;
                }

                if (isset($para->page)) {
                    try {
                        $page = (int) $para->page;
                        if ($para->page <= 0) {
                            $page = 1;
                        }
                    } catch (Exception $e) {
                        $page = 1;
                    }
                } else {
                    $page = 1;
                }
                if ($page > $view->pageNumber) {
                    $page = $view->pageNumber;
                }

                $view->page = $page;
                $startProduct = ($page - 1) * $view->products_per_page;
                $sqlLimit = " LIMIT " . $view->products_per_page . " OFFSET " . $startProduct;

                $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                $sth = $this->db->prepare($sqlAll);
                $sth->execute($paraSQL);
                $count = $sth->rowCount();
                if ($count > 0) {
                    $productList = $sth->fetchAll();
                    for ($i = 0; $i < sizeof($productList); $i++) {
                        $productInfo = $productList[$i];
                        Model::autoloadModel('image');
                        $imageModel = new ImageModel($this->db);
                        if (isset($productInfo->image_ids)) {
                            $image_ids = json_decode($productInfo->image_ids);
                            Model::autoloadModel('image');
                            $imageModel = new ImageModel($this->db);
                            $productInfo->images = $imageModel->getImagesByArray($image_ids);
                            if ($productInfo->images != null && is_array($productInfo->images) && count($productInfo->images) > 0) {
                                $productInfo->imagePresentation = $productInfo->images[0];
                            }
                        }
                        $productList[$i] = $productInfo;
                    }
                    $view->productList = $productList;
                } else {
                    $view->productList = NULL;
                }
            } else {
                $view->productList = NULL;
            }
        } catch (Exception $e) {
            $view->productList = NULL;
        }
    }
}

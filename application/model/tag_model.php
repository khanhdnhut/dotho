<?php
Model::autoloadModel('taxonomy');

class TagModel extends TaxonomyModel
{

    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = TAG_ERROR_ADD_NEW;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "tag") != FALSE) {
                $_SESSION["fb_error"][] = USER_ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = USER_ERROR_NAME_EMPTY;
            return false;
        }

        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "tag")) {
                $_SESSION["fb_error"][] = USER_ERROR_SLUG_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = USER_ERROR_SLUG_EMPTY;
            return false;
        }

        if (!(isset($para->parent) && $para->parent != "" && is_numeric($para->parent))) {
            $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->parent = (int) $para->parent;
            if ($para->parent < 0) {
                $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            }
        }

        return true;
    }

    public function addTagArray($tagArray)
    {
        $arrayTagId = array();
        try {
            if (is_array($tagArray) && count($tagArray) > 0) {
                for ($i = 0; $i < count($tagArray); $i++) {
                    $tagName = trim($tagArray[$i]);
                    if ($tagName != "") {
                        $resultCheckExist = $this->isExistName($tagName, "tag");
                        if ($resultCheckExist == FALSE) {
                            $tagSlug = Utils::createSlug($tagName);
                            if ($tagSlug != NULL) {
                                BO::autoloadBO("tag");
                                $tagBO = new TagBO();

                                if (isset($tagName)) {
                                    $tagBO->name = $tagName;
                                }
                                if (isset($tagSlug)) {
                                    $tagBO->slug = $tagSlug;
                                }
                                $tagBO->description = $tagName;
                                $tagBO->count = 0;
                                $tagBO->term_group = 0;
                                $tagBO->parent = 0;
                                $resultCheckExist = parent::addToDatabase($tagBO);
                                if ($resultCheckExist != NULL) {
                                    $arrayTagId[] = $resultCheckExist;
                                }
                            }
                        } else {
                            $arrayTagId[] = $resultCheckExist;
                        }
                    }
                }
                return $arrayTagId;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("tag");
                $tagBO = new TagBO();

                if (isset($para->name)) {
                    $tagBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $tagBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $tagBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $tagBO->parent = $para->parent;
                }
                $tagBO->count = 0;
                $tagBO->term_group = 0;

                $this->db->beginTransaction();

                if (parent::addToDatabase($tagBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = TAG_SUCCESS_ADD_NEW;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = TAG_SUCCESS_ADD_NEW;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = TAG_ERROR_ADD_NEW;
        }
        return FALSE;
    }

    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = TAG_ERROR_UPDATE_INFO;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = TAG_ERROR_UPDATE_INFO;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = TAG_ERROR_UPDATE_INFO;
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
        if (!(isset($para->parent) && $para->parent != "" && is_numeric($para->parent))) {
            $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->parent = (int) $para->parent;
            if ($para->parent < 0) {
                $_SESSION["fb_error"][] = USER_ERROR_PARENT_NOT_IMPOSSIBLE;
            }
        }
        return true;
    }

    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $tagBO = $this->get($para->term_taxonomy_id);
                if ($tagBO != NULL) {
                    if (isset($para->name)) {
                        $tagBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $tagBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $tagBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $tagBO->parent = $para->parent;
                    } else {
                        $tagBO->parent = 0;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($tagBO)) {
                        $this->db->commit();
                        $_SESSION["fb_success"][] = TAG_SUCCESS_UPDATE;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = TAG_ERROR_UPDATE_INFO;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = TAG_ERROR_UPDATE_INFO;
        }
        return FALSE;
    }

    public function updateTagsPerPages($tags_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "tags_per_page";
        $meta_value = $tags_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_tags_columns_show";
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
        if (isset($para->tags_per_page) && is_numeric($para->tags_per_page)) {
            $this->updateTagsPerPages($para->tags_per_page);
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
        if (isset($para->tags) && is_array($para->tags)) {
            foreach ($para->tags as $term_taxonomy_id) {
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

    public function searchAjax($view, $para)
    {
        $tags_per_page_ajax = TAGS_PER_PAGE_AJAX_DEFAULT;
        $taxonomy = "tag";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->tags_per_page_ajax) && is_numeric($userLoginBO->tags_per_page_ajax)) {
                $tags_per_page_ajax = (int) $userLoginBO->tags_per_page_ajax;
            }
        }

        if (!isset($tags_per_page_ajax)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->tags_per_page_ajax = TAGS_PER_PAGE_AJAX_DEFAULT;
                $tags_per_page_ajax = TAGS_PER_PAGE_AJAX_DEFAULT;
            } elseif (!isset($_SESSION['options']->tags_per_page_ajax)) {
                $_SESSION['options']->tags_per_page_ajax = TAGS_PER_PAGE_AJAX_DEFAULT;
                $tags_per_page_ajax = TAGS_PER_PAGE_AJAX_DEFAULT;
            }
        }

        $view->taxonomies_per_page = $tags_per_page_ajax;
        $view->taxonomy = $taxonomy;
        parent::search($view, $para);
    }

    public function search($view, $para)
    {
        $tags_per_page = TAGS_PER_PAGE_DEFAULT;
        $taxonomy = "tag";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->tags_per_page) && is_numeric($userLoginBO->tags_per_page)) {
                $tags_per_page = (int) $userLoginBO->tags_per_page;
            }
        }

        if (!isset($tags_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->tags_per_page = TAGS_PER_PAGE_DEFAULT;
                $tags_per_page = TAGS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->tags_per_page)) {
                $_SESSION['options']->tags_per_page = TAGS_PER_PAGE_DEFAULT;
                $tags_per_page = TAGS_PER_PAGE_DEFAULT;
            }
        }

        $view->taxonomies_per_page = $tags_per_page;
        $view->taxonomy = $taxonomy;
        parent::search($view, $para);
    }
}

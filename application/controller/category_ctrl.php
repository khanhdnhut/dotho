<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class CategoryCtrl extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    public function addNew()
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('category');
            $model = new CategoryModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['action']) && $_POST['action'] == "addNew") {
                $this->para->action = $_POST['action'];

                if (isset($_POST['name'])) {
                    $this->para->name = $_POST['name'];
                }
                if (isset($_POST['slug'])) {
                    $this->para->slug = $_POST['slug'];
                }
                if (isset($_POST['description'])) {
                    $this->para->description = $_POST['description'];
                }
                if (isset($_POST['parent'])) {
                    $this->para->parent = $_POST['parent'];
                }
                if (isset($_POST['tag_list'])) {
                    $this->para->tag_list = $_POST['tag_list'];
                }
                if (isset($_FILES['images']) && count($_FILES['images']) > 0 &&
                    isset($_FILES['images']['name']) && isset($_FILES['images']['name'][0]) &&
                    $_FILES['images']['name'][0] != '') {
                    $this->para->images = $_FILES['images'];
                }
                $result = $model->addToDatabase($this->para);
                if (!$result) {
                    $this->view->para = $this->para;
                }
            }

            $this->view->parentList = new SplDoublyLinkedList();
            $model->getAllSorted($this->view->parentList, $model->buildTree($model->getAll("category")), -1);

            if (isset($_POST['ajax']) && !is_null($_POST['ajax'])) {
                $this->view->renderAdmin(CATEGORY_RV_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(CATEGORY_RV_ADD_NEW);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }

    public function getNavBar()
    {
        Model::autoloadModel('category');
        $model = new CategoryModel($this->db);
//        $this->view->taxonomyList = $model->getByMetaData("category", "parent", $parent_id);
        $this->view->taxonomyList = new SplDoublyLinkedList();
        $model->getAllSorted($this->view->taxonomyList, $model->getAll("category"), -1);
        $this->view->render(CATEGORY_RV_NAVBAR, TRUE);
    }

    public function getSideBar()
    {
        Model::autoloadModel('category');
        $model = new CategoryModel($this->db);
        $this->view->taxonomyList = $model->getAll("category");
        for ($i = 0; $i < count($this->view->taxonomyList); $i++) {
            if (isset($this->view->taxonomyList[$i]->term_taxonomy_id)) {
                $this->view->taxonomyList[$i] = $model->get($this->view->taxonomyList[$i]->term_taxonomy_id);
            }
        }
        $this->view->render(CATEGORY_RV_SIDEBAR, TRUE);
    }

    public function getProductAllCategory()
    {
        Model::autoloadModel('category');
        $model = new CategoryModel($this->db);
        $this->view->taxonomyList = $model->getAll("category");
        for ($i = 0; $i < count($this->view->taxonomyList); $i++) {
            if (isset($this->view->taxonomyList[$i]->term_taxonomy_id)) {
                $this->view->taxonomyList[$i] = $model->get($this->view->taxonomyList[$i]->term_taxonomy_id);
            }
        }
        $this->view->render(CATEGORY_RV_ALL_CATEGORY, TRUE);
    }

    public function getByCountry($parent_id = NULL)
    {
        Model::autoloadModel('category');
        $model = new CategoryModel($this->db);
//        $this->view->taxonomyList = $model->getByMetaData("category", "parent", $parent_id);
        $this->view->taxonomyList = new SplDoublyLinkedList();
        $model->getAllSorted($this->view->taxonomyList, $model->buildTree($model->getByMetaData("category", "parent", $parent_id)), -1);
        $this->view->renderAdmin(CATEGORY_RV_SEARCH_BY_PARENT_AJAX, TRUE);
    }

    public function editInfo($term_taxonomy_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('category');
            $model = new CategoryModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['category'])) {
                $this->para->term_taxonomy_id = $_POST['category'];
            } elseif (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                $this->para->term_taxonomy_id = $term_taxonomy_id;
            }

            if (isset($this->para->term_taxonomy_id)) {
                if (isset($_POST['action']) && $_POST['action'] == "update") {
                    $this->para->action = $_POST['action'];

                    if (isset($_POST['name'])) {
                        $this->para->name = $_POST['name'];
                    }
                    if (isset($_POST['slug'])) {
                        $this->para->slug = $_POST['slug'];
                    }
                    if (isset($_POST['description'])) {
                        $this->para->description = $_POST['description'];
                    }
                    if (isset($_POST['parent'])) {
                        $this->para->parent = $_POST['parent'];
                    }
                    if (isset($_POST['image_delete_list'])) {
                        $this->para->image_delete_list = $_POST['image_delete_list'];
                    }
                    if (isset($_POST['tag_list'])) {
                        $this->para->tag_list = $_POST['tag_list'];
                    }
                    if (isset($_FILES['images']) && count($_FILES['images']) > 0 &&
                        isset($_FILES['images']['name']) && isset($_FILES['images']['name'][0]) &&
                        $_FILES['images']['name'][0] != "") {
                        $this->para->images = $_FILES['images'];
                    }

                    $result = $model->updateInfo($this->para);
                    if (!$result) {
                        $this->view->para = $this->para;
                    } else {
                        $update_success = TRUE;
                    }
                }
                $this->view->categoryBO = $model->get($this->para->term_taxonomy_id);

                $this->view->parentList = new SplDoublyLinkedList();
                $model->getAllSorted($this->view->parentList, $model->buildTree($model->getAll("category")), -1);

                if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                    $this->view->renderAdmin(CATEGORY_RV_EDIT);
                } else {
                    $this->view->renderAdmin(CATEGORY_RV_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . CATEGORY_CP_INDEX);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }

    public function info($term_taxonomy_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('category');
            $model = new CategoryModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['category'])) {
                $this->para->term_taxonomy_id = $_POST['category'];
            } elseif (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                $this->para->term_taxonomy_id = $term_taxonomy_id;
            }

            if (isset($this->para->term_taxonomy_id)) {
                $this->view->categoryBO = $model->get($this->para->term_taxonomy_id);
                $this->view->parentList = new SplDoublyLinkedList();
                $model->getAllSorted($this->view->parentList, $model->buildTree($model->getAll("category")), -1);

                if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                    $this->view->renderAdmin(CATEGORY_RV_INFO);
                } else {
                    $this->view->renderAdmin(CATEGORY_RV_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . CATEGORY_CP_INDEX);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }
    
    

    public function view($term_taxonomy_id = NULL, $page = 1)
    {
        if (!is_numeric($page) || $page < 1) {
            $page = 1;
            $this->view->page = 1;
        } else {
            $this->view->page = $page;
        }
        $number_results_per_page = DEFAULT_NUMBER_RESULTS_PER_PAGE;
        
        Model::autoloadModel('category');
        $model = new CategoryModel($this->db);
        $this->para = new stdClass();
        if (isset($_POST['category'])) {
            $this->para->term_taxonomy_id = $_POST['category'];
        } elseif (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
            $this->para->term_taxonomy_id = $term_taxonomy_id;
        }

        if (isset($this->para->term_taxonomy_id)) {
            $model->view($this->view, $this->para, $page, $number_results_per_page);
            if ($this->view->categoryBO = null) {
                header('location: ' . URL);
                return;
            }
            if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                $this->view->render(CATEGORY_RV_VIEW);
            } else {
                $this->view->render(CATEGORY_RV_VIEW, TRUE);
            }
        } else {
            header('location: ' . URL);
        }
    }
    
    public function view_ajax($term_taxonomy_id, $page = 1)
    {
        if (!is_numeric($page) || $page < 1) {
            $page = 1;
            $this->view->page = 1;
        } else {
            $this->view->page = $page;
        }
        $number_results_per_page = DEFAULT_NUMBER_PRODUCTS_PER_CATEGORY_PAGE_HOME;
        
        Model::autoloadModel('category');
        $model = new CategoryModel($this->db);
        $this->para = new stdClass();
        $this->para->term_taxonomy_id = $term_taxonomy_id;
        if (isset($this->para->term_taxonomy_id)) {
            $model->view($this->view, $this->para, $page, $number_results_per_page);
            if ($this->view->categoryBO = null) {
                return;
            }
            $this->view->render(CATEGORY_RV_VIEW_AJAX, TRUE);
        } 
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('category');
            $model = new CategoryModel($this->db);
            $this->para = new stdClass();

            if (isset($_POST['type'])) {
                $this->para->type = $_POST['type'];
            }
            if (isset($_POST['orderby'])) {
                $this->para->orderby = $_POST['orderby'];
            }
            if (isset($_POST['order'])) {
                $this->para->order = $_POST['order'];
            }
            if (isset($_POST['page'])) {
                $this->para->page = $_POST['page'];
            }
            if (isset($_POST['s'])) {
                $this->para->s = $_POST['s'];
            }
            if (isset($_POST['paged'])) {
                $this->para->paged = $_POST['paged'];
            }
            if (isset($_POST['categories'])) {
                $this->para->categories = $_POST['categories'];
            }
            if (isset($_POST['action'])) {
                $this->para->action = $_POST['action'];
            }
            if (isset($_POST['action2'])) {
                $this->para->action2 = $_POST['action2'];
            }
            if (isset($_POST['description_show'])) {
                $this->para->description_show = $_POST['description_show'];
            }
            if (isset($_POST['slug_show'])) {
                $this->para->slug_show = $_POST['slug_show'];
            }
            if (isset($_POST['level_show'])) {
                $this->para->level_show = $_POST['level_show'];
            }
            if (isset($_POST['tours_show'])) {
                $this->para->tours_show = $_POST['tours_show'];
            }
            if (isset($_POST['categories_per_page'])) {
                $this->para->categories_per_page = $_POST['categories_per_page'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2")) && isset($this->para->categories)) {
                $model->executeAction($this->para);
            }



            $model->search($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = TRUE;
                $this->view->renderAdmin(CATEGORY_RV_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(CATEGORY_RV_INDEX);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }
}

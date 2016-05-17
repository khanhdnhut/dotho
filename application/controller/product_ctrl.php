<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class ProductCtrl extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    public function getByCategory($category_id = NULL)
    {
        Model::autoloadModel('product');
        $model = new ProductModel($this->db);        
        $this->view->taxonomyList = $model->getPostRelationshipByTaxonomyId($category_id, "category");
        $this->view->renderAdmin(PRODUCT_RV_SEARCH_BY_CATEGORY_AJAX, TRUE);
    }
    
    public function addNew()
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('product');
            $model = new ProductModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['action']) && $_POST['action'] == "addNew") {
                $this->para->action = $_POST['action'];
                if (isset($_POST['post_title'])) {
                    $this->para->post_title = trim($_POST['post_title']);
                }
                if (isset($_POST['category_id']) && isset($_POST['category_id']) != "" && $_POST['category_id'] != "0") {
                    $this->para->category_id = $_POST['category_id'];
                }

                if (isset($_POST['tag_list'])) {
                    $this->para->tag_list = $_POST['tag_list'];
                }
                if (isset($_POST['post_content'])) {
                    $this->para->post_content = trim($_POST['post_content']);
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

            Model::autoloadModel('taxonomy');
            $taxonomyModel = new TaxonomyModel($this->db);

            $this->view->categoryList = new SplDoublyLinkedList();
            $taxonomyModel->getAllSorted($this->view->categoryList, $taxonomyModel->buildTree($taxonomyModel->getAll("category")), -1);

            if (isset($_POST['ajax']) && !is_null($_POST['ajax'])) {
                $this->view->renderAdmin(PRODUCT_RV_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(PRODUCT_RV_ADD_NEW);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }

    public function editInfo($post_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('product');
            $model = new ProductModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['product'])) {
                $this->para->post_id = $_POST['product'];
            } elseif (isset($post_id) && !is_null($post_id)) {
                $this->para->post_id = $post_id;
            }

            if (isset($this->para->post_id)) {
                if (isset($_POST['action']) && $_POST['action'] == "update") {
                    $this->para->action = $_POST['action'];

                    if (isset($_POST['post_title'])) {
                        $this->para->post_title = trim($_POST['post_title']);
                    }
                    if (isset($_POST['category_id']) && isset($_POST['category_id']) != "" && $_POST['category_id'] != "0") {
                        $this->para->category_id = $_POST['category_id'];
                    }
                    if (isset($_POST['tag_list'])) {
                        $this->para->tag_list = $_POST['tag_list'];
                    }
                    if (isset($_POST['post_content'])) {
                        $this->para->post_content = trim($_POST['post_content']);
                    }
                    if (isset($_POST['image_delete_list'])) {
                        $this->para->image_delete_list = $_POST['image_delete_list'];
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
                $this->view->productBO = $model->get($this->para->post_id);

                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);
                $this->view->categoryList = new SplDoublyLinkedList();
                $taxonomyModel->getAllSorted($this->view->categoryList, $taxonomyModel->buildTree($taxonomyModel->getAll("category")), -1);

                if (isset($post_id) && !is_null($post_id)) {
                    $this->view->renderAdmin(PRODUCT_RV_EDIT);
                } else {
                    $this->view->renderAdmin(PRODUCT_RV_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . PRODUCT_RV_INDEX);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }

    public function info($post_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('product');
            $model = new ProductModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['product'])) {
                $this->para->post_id = $_POST['product'];
            } elseif (isset($post_id) && !is_null($post_id)) {
                $this->para->post_id = $post_id;
            }

            if (isset($this->para->post_id)) {
                $this->view->productBO = $model->get($this->para->post_id);


                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);

                $this->view->categoryList = new SplDoublyLinkedList();
                $taxonomyModel->getAllSorted($this->view->categoryList, $taxonomyModel->buildTree($taxonomyModel->getAll("category")), -1);

                if (isset($post_id) && !is_null($post_id)) {
                    $this->view->renderAdmin(PRODUCT_RV_INFO);
                } else {
                    $this->view->renderAdmin(PRODUCT_RV_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . PRODUCT_RV_INDEX);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }

    public function view($post_id = NULL)
    {
        Model::autoloadModel('product');
        $model = new ProductModel($this->db);
        $this->para = new stdClass();
        if (isset($_POST['product'])) {
            $this->para->post_id = $_POST['product'];
        } elseif (isset($post_id) && !is_null($post_id)) {
            $this->para->post_id = $post_id;
        }

        if (isset($this->para->post_id)) {
            $this->view->productBO = $model->get($this->para->post_id);
            if ($this->view->productBO != null) {
                $this->view->metadata = new stdClass();
                $url = URL . PRODUCT_CP_VIEW . $this->view->productBO->ID . "/" . $this->view->productBO->post_name;
                $this->view->metadata->canonical = $url;
                $this->view->metadata->url = $url;
                $this->view->metadata->title = $this->view->productBO->post_title;                
                $this->view->metadata->keywords = DEFAULT_KEYWORD;
                
                
                Model::autoloadModel('category');
                $categoryModel = new CategoryModel($this->db);
                $this->view->categoryList = $categoryModel->getAll("category");

                if (isset($this->view->productBO->category_id) && is_array($this->view->categoryList) && count($this->view->categoryList) > 0) {
                    $category = $categoryModel->getCategoryByIDFromArray($this->view->productBO->category_id, $this->view->categoryList);                    
                    if ($category != null) {
                        $this->view->metadata->keywords .= ", " . strtolower($category->name) .
                            ", " . strtolower(Utils::convertTVKhongDau($category->name));
                        $this->view->productBO->categoryArray[] = $category;
                        while ($this->view->productBO->categoryArray[(count($this->view->productBO->categoryArray) - 1)]->parent != '0') {
                            $parent_id = $this->view->productBO->categoryArray[(count($this->view->productBO->categoryArray) - 1)]->parent;
                            $category = $categoryModel->getCategoryByIDFromArray($parent_id, $this->view->categoryList);
                            if ($category != null) {
                                $this->view->productBO->categoryArray[] = $category;
                                $this->view->metadata->keywords .= ", " . strtolower($category->name) .
                            ", " . strtolower(Utils::convertTVKhongDau($category->name));
                            } else {
                                break;
                            }
                        }
                    }
                }
            } else {
                header('location: ' . URL);
                return;
            }
            if (isset($post_id) && !is_null($post_id)) {
                $this->view->render(PRODUCT_RV_VIEW);
            } else {
                $this->view->render(PRODUCT_RV_VIEW, TRUE);
            }
        } else {
            header('location: ' . URL);
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('product');
            $model = new ProductModel($this->db);
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
            if (isset($_POST['products'])) {
                $this->para->products = $_POST['products'];
            }
            if (isset($_POST['action'])) {
                $this->para->action = $_POST['action'];
            }
            if (isset($_POST['action2'])) {
                $this->para->action2 = $_POST['action2'];
            }
            if (isset($_POST['products_per_page'])) {
                $this->para->products_per_page = $_POST['products_per_page'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2")) && isset($this->para->products)) {
                $model->executeAction($this->para);
            }

            $model->search($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = TRUE;
                $this->view->renderAdmin(PRODUCT_RV_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(PRODUCT_RV_INDEX);
            }
        } else {
            Controller::autoloadController('user');
            $userCtrl = new UserCtrl();
            $userCtrl->login();
        }
    }
}

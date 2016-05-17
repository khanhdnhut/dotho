<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class UserCtrl extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        if (empty($_POST['log'])) {
            if (Auth::isLogged()) {
                header('location: ' . URL . ADMIN_CP);
            } else {
                $this->view->renderAdmin(USER_RV_LOGIN, TRUE);
            }
        } else {
            //Thực hiện đăng nhập
            // run the login() method in the login-model, put the result in $login_successful (true or false)
            Model::autoloadModel('User');
            $model = new UserModel($this->db);

            if ($model->loginValidate()) {
                if ($model->login($_POST['log'])) {
                    header('location: ' . URL . ADMIN_CP);
                } else {
                    $this->view->renderAdmin(USER_RV_LOGIN, TRUE);
                }
            } else {
                $this->view->renderAdmin(USER_RV_LOGIN, TRUE);
            }
        }
    }

    public function logout()
    {
        Model::autoloadModel('User');
        $model = new UserModel($this->db);
        $model->logout();
        // redirect user to base URL
        header('location: ' . URL . USER_CP_LOGIN);
    }

    public function addNew()
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('user');
            $model = new UserModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['action']) && $_POST['action'] == "addNew") {
                $this->para->action = $_POST['action'];

                if (isset($_POST['user_login'])) {
                    $this->para->user_login = $_POST['user_login'];
                }
                if (isset($_POST['role'])) {
                    $this->para->role = $_POST['role'];
                }
                if (isset($_POST['first_name'])) {
                    $this->para->first_name = $_POST['first_name'];
                }
                if (isset($_POST['last_name'])) {
                    $this->para->last_name = $_POST['last_name'];
                }
                if (isset($_POST['nickname'])) {
                    $this->para->nickname = $_POST['nickname'];
                }
                if (isset($_POST['display_name'])) {
                    $this->para->display_name = $_POST['display_name'];
                }
                if (isset($_POST['email'])) {
                    $this->para->email = $_POST['email'];
                }
                if (isset($_POST['url'])) {
                    $this->para->url = $_POST['url'];
                }
                if (isset($_POST['description'])) {
                    $this->para->description = $_POST['description'];
                }
                if (isset($_FILES['avatar']) && isset($_FILES['avatar']['name']) &&
                    $_FILES['avatar']['name'] != '') {
                    $this->para->avatar = $_FILES['avatar'];
                }
                if (isset($_POST['pass1'])) {
                    $this->para->pass1 = $_POST['pass1'];
                }
                if (isset($_POST['pass1-text'])) {
                    $this->para->pass1_text = $_POST['pass1-text'];
                }
                if (isset($_POST['pw_weak'])) {
                    $this->para->pw_weak = $_POST['pw_weak'];
                }
                $result = $model->addNew($this->para);
                if (!$result) {
                    $this->view->para = $this->para;
                }
            }

            if (isset($_POST['ajax']) && !is_null($_POST['ajax'])) {
                $this->view->renderAdmin(USER_RV_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(USER_RV_ADD_NEW);
            }
        } else {
            $this->login();
        }
    }

    public function editInfo($user_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('user');
            $model = new UserModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['user'])) {
                $this->para->user_id = $_POST['user'];
            } elseif (isset($user_id) && !is_null($user_id)) {
                $this->para->user_id = $user_id;
            }

            if (isset($this->para->user_id)) {
                if (isset($_POST['action']) && $_POST['action'] == "update") {
                    $this->para->action = $_POST['action'];

                    if (isset($_POST['role'])) {
                        $this->para->role = $_POST['role'];
                    }
                    if (isset($_POST['first_name'])) {
                        $this->para->first_name = $_POST['first_name'];
                    }
                    if (isset($_POST['last_name'])) {
                        $this->para->last_name = $_POST['last_name'];
                    }
                    if (isset($_POST['nickname'])) {
                        $this->para->nickname = $_POST['nickname'];
                    }
                    if (isset($_POST['display_name'])) {
                        $this->para->display_name = $_POST['display_name'];
                    }
                    if (isset($_POST['email'])) {
                        $this->para->email = $_POST['email'];
                    }
                    if (isset($_POST['url'])) {
                        $this->para->url = $_POST['url'];
                    }
                    if (isset($_POST['description'])) {
                        $this->para->description = $_POST['description'];
                    }
                    if (isset($_FILES['avatar']) && isset($_FILES['avatar']['name']) &&
                        $_FILES['avatar']['name'] != '') {
                        $this->para->avatar = $_FILES['avatar'];
                    }
                    if (isset($_POST['pass1'])) {
                        $this->para->pass1 = $_POST['pass1'];
                    }
                    if (isset($_POST['pass1-text'])) {
                        $this->para->pass1_text = $_POST['pass1-text'];
                    }
                    if (isset($_POST['pw_weak'])) {
                        $this->para->pw_weak = $_POST['pw_weak'];
                    }
                    $result = $model->updateInfo($this->para);
                    if (!$result) {
                        $this->view->para = $this->para;
                    } else {
                        $update_success = TRUE;
                    }
                }
                $this->view->userBO = $model->get($this->para->user_id);
                if (Session::get('user_id') == $this->para->user_id && isset($update_success) && $update_success) {
                    $model->setNewSessionUser($this->view->userBO);
                }
                if (isset($user_id) && !is_null($user_id)) {
                    $this->view->renderAdmin(USER_RV_EDIT);
                } else {
                    $this->view->renderAdmin(USER_RV_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . USER_CP_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function info($user_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('User');
            $model = new UserModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['user'])) {
                $this->para->user_id = $_POST['user'];
            } elseif (isset($user_id) && !is_null($user_id)) {
                $this->para->user_id = $user_id;
            }

            if (isset($this->para->user_id)) {
                $this->view->userBO = $model->get($this->para->user_id);
                if (isset($user_id) && !is_null($user_id)) {
                    $this->view->renderAdmin(USER_RV_INFO);
                } else {
                    $this->view->renderAdmin(USER_RV_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . USER_CP_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function profile()
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('User');
            $model = new UserModel($this->db);
            $this->para = new stdClass();
            $this->para->user_id = Session::get('user_id');

            if (isset($this->para->user_id)) {
                $this->view->userBO = $model->get($this->para->user_id);
                $this->view->renderAdmin(USER_RV_INFO);
            } else {
                header('location: ' . URL . USER_CP_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(USER_VALUE_ADMIN))) {
            Model::autoloadModel('User');
            $model = new UserModel($this->db);
            $this->para = new stdClass();

            if (isset($_POST['type'])) {
                $this->para->type = $_POST['type'];
            }
            if (isset($_POST['role'])) {
                $this->para->role = $_POST['role'];
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
            if (isset($_POST['users'])) {
                $this->para->users = $_POST['users'];
            }
            if (isset($_POST['new_role'])) {
                $this->para->new_role = $_POST['new_role'];
            }
            if (isset($_POST['new_role2'])) {
                $this->para->new_role2 = $_POST['new_role2'];
            }
            if (isset($_POST['action'])) {
                $this->para->action = $_POST['action'];
            }
            if (isset($_POST['action2'])) {
                $this->para->action2 = $_POST['action2'];
            }

            if (isset($_POST['email_show'])) {
                $this->para->email_show = $_POST['email_show'];
            }
            if (isset($_POST['role_show'])) {
                $this->para->role_show = $_POST['role_show'];
            }
            if (isset($_POST['tours_show'])) {
                $this->para->tours_show = $_POST['tours_show'];
            }
            if (isset($_POST['users_per_page'])) {
                $this->para->users_per_page = $_POST['users_per_page'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2", "new_role", "new_role2")) && isset($this->para->users)) {
                if (in_array($this->para->type, array("action", "action2"))) {
                    $model->executeAction($this->para);
                } else {
                    $model->changeRole($this->para);
                }
            }

            $model->search($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = TRUE;
                $this->view->renderAdmin(USER_RV_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(USER_RV_INDEX);
            }
        } else {
            $this->login();
        }
    }
}

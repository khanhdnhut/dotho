<?php
/*
 * 
 * 
 */

/**
 * Class Auth
 * Simply checks if user is logged in. In the app, several controllers use Auth::handleLogin() to
 * check if user is logged in, useful to show controllers/methods only to logged-in users.
 * @author khanh_000
 */
class Auth
{

    public static function handleLogin()
    {
        // Initialize the session
        Session::init();

        // If user is still not logged in, then destroy session, handle user as "Not logged in"
        // and redirect user to login page
        if (!isset($_SESSION['user_logged_in'])) {
            Session::destroy();
            header('location: ' . URL . 'user/login');
            return FALSE;
        }
        return TRUE;
    }

    public static function isLogged()
    {
        // Initialize the session
        Session::init();

        // If user is still not logged in, then destroy session, handle user as "Not logged in"
        // and redirect user to login page
        if (!isset($_SESSION['user_logged_in'])) {
            Session::destroy();
            return FALSE;
        }
        return TRUE;
    }

    public static function getCapability()
    {
        // Initialize the session
        Session::init();
        // If user is still not logged in, then destroy session, handle user as "Not logged in"
        // and redirect user to login page
        if (isset($_SESSION['user_logged_in'])) {
            if (Session::get('userInfo') !=
                NULL) {
                if (Session::get('capability') !=
                    NULL) {
                    return Session::get('capability');
                } else {
                    $userBO = json_decode(Session::get('userInfo'));
                    Session::set("capability", $userBO->wp_capabilities);
                    return $userBO->wp_capabilities;
                }
            }
        }
        return NULL;
    }
}

?>

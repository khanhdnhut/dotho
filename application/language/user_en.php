<?php
/*
 * User
 */
define("USER_VALUE_ADMIN", "administrator");
define("USER_VALUE_SUBSCRIBER", "subscriber");
define("USER_VALUE_CONTRIBUTOR", "contributor");
define("USER_VALUE_AUTHOR", "author");
define("USER_VALUE_EDITOR", "editor");

define("USER_TITLE_ADMIN", "Administrator");
define("USER_TITLE_EDITOR", "Editor");
define("USER_TITLE_AUTHOR", "Author");
define("USER_TITLE_CONTRIBUTOR", "Contributor");
define("USER_TITLE_SUBSCRIBER", "Subscriber");

/*
 * Language for: views/user/login.php
 */

define("USER_TITLE_PASSWORD", "Password");
define("USER_TITLE_REMEMBER_ME", "Remember Me");
define("USER_TITLE_LOG_IN", "Log In");
define("USER_TITLE_LOST_PASS", "Lost your password?");
define("USER_DESC_LOST_PASS", "Password Lost and Found");

define("USER_ERROR_PASSWORD_EMPTY", "Password field was empty.");
define("USER_ERROR_USERNAME_EMPTY", "Username field was empty.");
define("USER_ERROR_LOGIN_FAILED", "Username or password is incorrect");
define("USER_ERROR_USER_NOT_ACTIVE", "Your account is not activated yet. Please contact administrator to active your account.");
define("USER_ERROR_USER_BLOCKED", "Your account blocked. Please contact administrator to unblock your account.");
define("USER_ERROR_USER_INFO_NOT_FOUND", "Not found user");

define("USER_TITLE_USERNAME", "Username");
define("USER_TITLE_EDIT_USER", "Edit User");
define("USER_TITLE_USERS", "Users");
define("USER_TITLE_YOUR_PROFILE", "Your Profile");
define("USER_TITLE_ALL_USERS", "All Users");
define("USER_TITLE_FILTER_USERS_LIST", "Filter users list");
define("USER_VALUE_NUMBER_SEARCH_USER", "number_search_user");
define("USER_TITLE_SEARCH_USERS", "Search Users");
define("USER_TITLE_USERS_LIST_NAVIGATION", "Users list navigation");
define("USER_TITLE_USERS_LIST", "Users list");
define("USER_DESC_USER_NAME", "Usernames cannot be changed.");
define("USER_TITLE_USER_WEBSITE", "Website");
define("USER_TITLE_ABOUT", "About");
define("USER_ERROR_DELETE_USER_NOT_PERMISSION", "You don't have permission to delete user");
define("USER_ERROR_ADD_NEW_USER", "Add new user failed!");
define("USER_ERROR_PASSWORD_INVALID", "Password of user is invalid!");
define("USER_ERROR_UPDATE_INFO_USER", "Update profile of user failed!");
define("USER_ERROR_ROLE_OF_USER", "Role of user is not impossible");
define("USER_ERROR_LOGIN", "Username is empty");
define("USER_ERROR_USER_EXISTED", "Username existed");
define("USER_ERROR_EMAIL_EMPTY", "Email of user is empty");
define("USER_ERROR_EMAIL_INVALID", "Invalid email format");
define("USER_SUCCESS_ADD_USER", "Add new user successed!");
define("USER_SUCCESS_UPDATE_USER", "Update profile of user successed!");
define("USER_TITLE_NEW_USER", "Add New User");
define("USER_TITLE_UPDATE_USER", "Update User");
define("USER_TITLE_USER", "user");

/*
 * Language for: view/user/edit.php
 */

define("USER_TITLE_CHANGE_ROLE_TO", "Change role to");
define("USER_TITLE_EMAIL", "Email");
define("USER_TITLE_ROLE", "Role");
define("USER_TITLE_PROFILE_OF", "Profile of ");
define("USER_TITLE_EDIT_PROFILE_OF", "Edit profile of ");
define("USER_TITLE_FIRST_NAME", "First Name");
define("USER_TITLE_LAST_NAME", "Last Name");
define("USER_TITLE_NICKNAME", "Nickname");
define("USER_TITLE_DISPLAY_NAME", "Display name publicly as");
define("USER_TITLE_CONTACT_INFO", "Contact Info");
define("USER_TITLE_BIOGRAPHICAL_INFO", "Biographical Info");
define("USER_TITLE_PROFILE_PICTURE", "Profile picture");
define("USER_VALUE_AVATAR_DEFAULT", "public/img/icon/no_avatar.jpg");
define("USER_TITLE_YOU", "You");

define("USER_TITLE_SHOW_PASSWORD", "Show password");
define("USER_TITLE_GENERATE_PASSWORD", "Generate Password");
define("USER_TITLE_ACCOUNT_MANAGEMENT", "Account Management");
define("USER_TITLE_NEW_PASSWORD", "New Password");


define("USER_TITLE_CONFIRM_PASSWORD", "Confirm Password");
define("USER_DESC_CONFIRM_PASSWORD", "Confirm use of weak password");
define("USER_CONFIRM_DELETE_USER", "You are about to permanently delete these user: ");
define("USER_CONFIRM_EDIT_INFO_USER", "You are about to permanently edit these user: ");
define("USER_ERROR_UPDATE_INFO_USER_CONFIRM_WEAK_PASS", "Password of user is weak, you must confirm use of weak password!");



define("USER_ERROR_UPDATE_AVATAR_FAILED", "Update avatar failed!");
define("USER_ERROR_UPLOAD_AVATAR_FAILED", "Upload avatar failed!");


define("USER_ERROR_EMAIL_IS_EMPTY", "Error: Email is emtpy!");
define("USER_ERROR_PASSWORD_IS_EMPTY", "Error: Password is emtpy!");

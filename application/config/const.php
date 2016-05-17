<?php
define("USER_STATUS_ACTIVE", 0);
define("USER_STATUS_NOT_ACTIVE", 1);
define("USER_STATUS_BLOCKED", 2);
define("USER_STATUS_DELETED", 3);

/*
 * CONSTANT for DATABASE
 */
define("TABLE_COMMENTMETA", "commentmeta");
define("TABLE_COMMENTS", "comments");
define("TABLE_LINKS", "links");
define("TABLE_OPTIONS", "options");
define("TABLE_POSTMETA", "postmeta");
define("TABLE_POSTS", "posts");
define("TABLE_TERM_RELATIONSHIPS", "term_relationships");
define("TABLE_TERM_TAXONOMY", "term_taxonomy");
define("TABLE_TERMMETA", "termmeta");
define("TABLE_TERMS", "terms");
define("TABLE_USERMETA", "usermeta");
define("TABLE_USERS", "users");

/*
 * CONSTANT for: COMMENTMETA TABLE
 */
define("TB_COMMENTMETA_COL_META_ID", "meta_id");
define("TB_COMMENTMETA_COL_COMMENT_ID", "comment_id");
define("TB_COMMENTMETA_COL_META_KEY", "meta_key");
define("TB_COMMENTMETA_COL_META_VALUE", "meta_key");

/*
 * CONSTANT for: COMMENT TABLE
 */
define("TB_COMMENT_COL_COMMENT_ID", "comment_ID");
define("TB_COMMENT_COL_COMMENT_POST_ID", "comment_post_ID");
define("TB_COMMENT_COL_COMMENT_AUTHOR", "comment_author");
define("TB_COMMENT_COL_COMMENT_AUTHOR_EMAIL", "comment_author_email");
define("TB_COMMENT_COL_COMMENT_AUTHOR_URL", "comment_author_url");
define("TB_COMMENT_COL_COMMENT_AUTHOR_IP", "comment_author_IP");
define("TB_COMMENT_COL_COMMENT_DATE", "comment_date");
define("TB_COMMENT_COL_COMMENT_DATE_GMT", "comment_date_gmt");
define("TB_COMMENT_COL_COMMENT_CONTENT", "comment_content");
define("TB_COMMENT_COL_COMMENT_KARMA", "comment_karma");
define("TB_COMMENT_COL_COMMENT_APPROVED", "comment_approved");
define("TB_COMMENT_COL_COMMENT_AGENT", "comment_agent");
define("TB_COMMENT_COL_COMMENT_TYPE", "comment_type");
define("TB_COMMENT_COL_COMMENT_PARENT", "comment_parent");
define("TB_COMMENT_COL_USER_ID", "user_id");

/*
 * CONSTANT for: LINKS TABLE
 */
define("TB_LINKS_COL_LINK_ID", "link_id");
define("TB_LINKS_COL_LINK_URL", "link_url");
define("TB_LINKS_COL_LINK_NAME", "link_name");
define("TB_LINKS_COL_LINK_IMAGE", "link_image");
define("TB_LINKS_COL_LINK_TARGET", "link_target");
define("TB_LINKS_COL_LINK_DESCRIPTION", "link_description");
define("TB_LINKS_COL_LINK_VISIBLE", "link_visible");
define("TB_LINKS_COL_LINK_OWNER", "link_owner");
define("TB_LINKS_COL_LINK_RATING", "link_rating");
define("TB_LINKS_COL_LINK_UPDATED", "link_updated");
define("TB_LINKS_COL_LINK_REL", "link_rel");
define("TB_LINKS_COL_LINK_NOTES", "link_notes");
define("TB_LINKS_COL_LINK_RSS", "link_rss");

/*
 * CONSTANT for: LINKS TABLE
 */
define("TB_OPTIONS_COL_OPTION_ID", "option_id");
define("TB_OPTIONS_COL_OPTION_NAME", "option_name");
define("TB_OPTIONS_COL_OPTION_VALUE", "option_value");
define("TB_OPTIONS_COL_OPTION_AUTOLOAD", "autoload");

/*
 * CONSTANT for: POSTMETA TABLE
 */
define("TB_POSTMETA_COL_META_ID", "meta_id");
define("TB_POSTMETA_COL_POST_ID", "post_id");
define("TB_POSTMETA_COL_META_KEY", "meta_key");
define("TB_POSTMETA_COL_META_VALUE", "meta_value");

/*
 * CONSTANT for: POST TABLE
 */
define("TB_POST_COL_ID", "ID");
define("TB_POST_COL_POST_AUTHOR", "post_author");
define("TB_POST_COL_POST_DATE", "post_date");
define("TB_POST_COL_POST_DATE_GMT", "post_date_gmt");
define("TB_POST_COL_POST_CONTENT", "post_content");
define("TB_POST_COL_POST_TITLE", "post_title");
define("TB_POST_COL_POST_EXCERPT", "post_excerpt");
define("TB_POST_COL_POST_STATUS", "post_status");
define("TB_POST_COL_COMMENT_STATUS", "comment_status");
define("TB_POST_COL_PING_STATUS", "ping_status");
define("TB_POST_COL_POST_PASSWORD", "post_password");
define("TB_POST_COL_POST_NAME", "post_name");
define("TB_POST_COL_TO_PING", "to_ping");
define("TB_POST_COL_PINGED", "pinged");
define("TB_POST_COL_POST_MODIFIED", "post_modified");
define("TB_POST_COL_POST_MODIFIED_GMT", "post_modified_gmt");
define("TB_POST_COL_POST_CONTENT_FILTERED", "post_content_filtered");
define("TB_POST_COL_POST_PARENT", "post_parent");
define("TB_POST_COL_GUID", "guid");
define("TB_POST_COL_MENU_ORDER", "menu_order");
define("TB_POST_COL_POST_TYPE", "post_type");
define("TB_POST_COL_POST_MIME_TYPE", "post_mime_type");
define("TB_POST_COL_COMMENT_COUNT", "comment_count");

/*
 * CONSTANT for: TERM_RELATIONSHIPS TABLE
 */
define("TB_TERM_RELATIONSHIPS_COL_OBJECT_ID", "object_id");
define("TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID", "term_taxonomy_id");
define("TB_TERM_RELATIONSHIPS_COL_TERM_ORDER", "term_order");

/*
 * CONSTANT for: TERM_TAXONOMY TABLE
 */
define("TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID", "term_taxonomy_id");
define("TB_TERM_TAXONOMY_COL_TERM_ID", "term_id");
define("TB_TERM_TAXONOMY_COL_TAXONOMY", "taxonomy");
define("TB_TERM_TAXONOMY_COL_DESCRIPTION", "description");
define("TB_TERM_TAXONOMY_COL_PARENT", "parent");
define("TB_TERM_TAXONOMY_COL_COUNT", "count");

/*
 * CONSTANT for: TERMMETA TABLE
 */
define("TB_TERMMETA_COL_META_ID", "meta_id");
define("TB_TERMMETA_COL_TERM_ID", "term_id");
define("TB_TERMMETA_COL_META_KEY", "meta_key");
define("TB_TERMMETA_COL_META_VALUE", "meta_value");

/*
 * CONSTANT for: TERMS TABLE
 */
define("TB_TERMS_COL_TERM_ID", "term_id");
define("TB_TERMS_COL_NAME", "name");
define("TB_TERMS_COL_SLUG", "slug");
define("TB_TERMS_COL_TERM_GROUP", "term_group");

/*
 * CONSTANT for: USERMETA TABLE
 */
define("TB_USERMETA_COL_UMETA_ID", "umeta_id");
define("TB_USERMETA_COL_USER_ID", "user_id");
define("TB_USERMETA_COL_META_KEY", "meta_key");
define("TB_USERMETA_COL_META_VALUE", "meta_value");
define("WP_CAPABILITIES", "wp_capabilities");
define("USER_ID", "user_id");

/*
 * CONSTANT for: USERS TABLE
 */
define("TB_USERS_COL_ID", "ID");
define("TB_USERS_COL_USER_LOGIN", "user_login");
define("TB_USERS_COL_USER_PASS", "user_pass");
define("TB_USERS_COL_USER_NICENAME", "user_nicename");
define("TB_USERS_COL_USER_EMAIL", "user_email");
define("TB_USERS_COL_USER_URL", "user_url");
define("TB_USERS_COL_USER_REGISTERED", "user_registered");
define("TB_USERS_COL_USER_ACTIVATION_KEY", "user_activation_key");
define("TB_USERS_COL_USER_STATUS", "user_status");
define("TB_USERS_COL_DISPLAY_NAME", "display_name");


/*
 * CONSTANT for: IMAGE
 */
define("SIZE_WITH_SLIDER_THUMB", 100);
define("SIZE_WITH_THUMBNAIL", 150);
define("SIZE_WITH_POST_THUMBNAIL", 200);
define("SIZE_WITH_MEDIUM", 300);
define("SIZE_WITH_MEDIUM_LARGE", 768);
define("SIZE_WITH_LARGE", 1024);


define("USERS_PER_PAGE_DEFAULT", 10);
define("CATEGORIES_PER_PAGE_DEFAULT", 10);
define("PRODUCTS_PER_PAGE_DEFAULT", 10);
define("TAGS_PER_PAGE_DEFAULT", 10);
define("TAGS_PER_PAGE_AJAX_DEFAULT", 10);
define("POST_STATUS_PENDING", "pending");
define("POST_STATUS_DRAFT", "draft");



define("DEFAULT_NUMBER_RESULTS_PER_PAGE", 20);
define("DEFAULT_NUMBER_PRODUCTS_PER_CATEGORY_PAGE_HOME", 4);

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PostBO extends BO 
{
    public $ID;
    public $post_author = 0;
    public $post_date;
    public $post_date_gmt;
    public $post_content = "";
    public $post_title = "";
    public $post_excerpt = "";
    public $post_status = "publish";
    public $comment_status = "closed";
    public $ping_status = "open";
    public $post_password = "";
    public $post_name = "";
    public $to_ping = "";
    public $pinged = "";
    public $post_modified;
    public $post_modified_gmt;
    public $post_content_filtered = "";
    public $post_parent = 0;
    public $guid = "";
    public $menu_order = 0;
    public $post_type = "post";
    public $post_mime_type = "";
    public $comment_count = 0;

    function __construct()
    {
        
    }    
    
    public function setPost($postInfo) {
        if (!is_null($postInfo)) {
            if (isset($postInfo->ID)) {
                $this->ID = $postInfo->ID;
            }
            if (isset($postInfo->comment_count)) {
                $this->comment_count = $postInfo->comment_count;
            }
            if (isset($postInfo->comment_status)) {
                $this->comment_status = $postInfo->comment_status;
            }
            if (isset($postInfo->guid)) {
                $this->guid = $postInfo->guid;
            }
            if (isset($postInfo->menu_order)) {
                $this->menu_order = $postInfo->menu_order;
            }
            if (isset($postInfo->ping_status)) {
                $this->ping_status = $postInfo->ping_status;
            }
            if (isset($postInfo->pinged)) {
                $this->pinged = $postInfo->pinged;
            }
            if (isset($postInfo->post_author)) {
                $this->post_author = $postInfo->post_author;
            }
            if (isset($postInfo->post_content)) {
                $this->post_content = $postInfo->post_content;
            }
            if (isset($postInfo->post_content_filtered)) {
                $this->post_content_filtered = $postInfo->post_content_filtered;
            }
            if (isset($postInfo->post_date)) {
                $this->post_date = $postInfo->post_date;
            }
            if (isset($postInfo->post_date_gmt)) {
                $this->post_date_gmt = $postInfo->post_date_gmt;
            }
            if (isset($postInfo->post_excerpt)) {
                $this->post_excerpt = $postInfo->post_excerpt;
            }
            if (isset($postInfo->post_mime_type)) {
                $this->post_mime_type = $postInfo->post_mime_type;
            }
            if (isset($postInfo->post_modified)) {
                $this->post_modified = $postInfo->post_modified;
            }
            if (isset($postInfo->post_modified_gmt)) {
                $this->post_modified_gmt = $postInfo->post_modified_gmt;
            }
            if (isset($postInfo->post_name)) {
                $this->post_name = $postInfo->post_name;
            }
            if (isset($postInfo->post_parent)) {
                $this->post_parent = $postInfo->post_parent;
            }
            if (isset($postInfo->post_password)) {
                $this->post_password = $postInfo->post_password;
            }
            if (isset($postInfo->post_status)) {
                $this->post_status = $postInfo->post_status;
            }
            if (isset($postInfo->post_title)) {
                $this->post_title = $postInfo->post_title;
            }
            if (isset($postInfo->post_type)) {
                $this->post_type = $postInfo->post_type;
            }
            if (isset($postInfo->to_ping)) {
                $this->to_ping = $postInfo->to_ping;
            }
        }
    }

    public function setPostMetaInfo($postMetaInfoArray) {
        if (!is_null($postMetaInfoArray) && is_array($postMetaInfoArray) && count($postMetaInfoArray) > 0) {
            foreach ($postMetaInfoArray as $postMetaInfo) {
                if (isset($postMetaInfo->post_id) && $postMetaInfo->post_id == $this->ID) {
                    if (isset($postMetaInfo->meta_key) && isset($postMetaInfo->meta_value)) {
                        $meta_key = $postMetaInfo->meta_key;
                        $this->$meta_key = $postMetaInfo->meta_value;                        
                    }
                }
            }
        }
    }
}

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserBO extends BO
{

    public $user_id = null;
    public $user_login = null;
    public $user_pass = null;
    public $user_nicename = null;
    public $user_email = null;
    public $user_url = null;
    public $user_registered = null;
    public $user_activation_key = null;
    public $user_status = null;
    public $display_name = null;
    public $nickname = null;
    public $first_name = null;
    public $last_name = null;
    public $description = null;
    public $avatar = null;
    public $avatar_object = null;
    public $avatar_url = null;
    public $wp_capabilities = null;
    public $session_tokens = null;
    
    public $users_per_page = null;
    public $manage_users_columns_show = null;
    
    public $categories_per_page = null;
    public $manage_categories_columns_show = null;
    
    public $products_per_page = null;
    public $manage_products_columns_show = null;
    
    public $tags_per_page = null;
    public $tags_per_page_ajax = null;
    public $manage_tags_columns_show = null;
    

    public function setUserInfo($userInfo)
    {
        if (!is_null($userInfo)) {
            if (isset($userInfo->ID)) {
                $this->user_id = $userInfo->ID;
            }
            if (isset($userInfo->user_login)) {
                $this->user_login = $userInfo->user_login;
            }
            if (isset($userInfo->user_pass)) {
                $this->user_pass = $userInfo->user_pass;
            }
            if (isset($userInfo->user_nicename)) {
                $this->user_nicename = $userInfo->user_nicename;
            }
            if (isset($userInfo->user_email)) {
                $this->user_email = $userInfo->user_email;
            }
            if (isset($userInfo->user_url)) {
                $this->user_url = $userInfo->user_url;
            }
            if (isset($userInfo->user_registered)) {
                $this->user_registered = $userInfo->user_registered;
            }
            if (isset($userInfo->user_activation_key)) {
                $this->user_activation_key = $userInfo->user_activation_key;
            }
            if (isset($userInfo->user_status)) {
                $this->user_status = $userInfo->user_status;
            }
            if (isset($userInfo->display_name)) {
                $this->display_name = $userInfo->display_name;
            }
        }
    }

    public function setUserMetaInfo($userMetaInfoArray)
    {
        //Kết quả tìm kiếm từ bảng userMeta
        if (!is_null($userMetaInfoArray) && is_array($userMetaInfoArray) && count($userMetaInfoArray) > 0) {
            foreach ($userMetaInfoArray as $userMeta) {
                if (isset($userMeta->user_id) && $userMeta->user_id == $this->user_id) {
//                    if (isset($userMeta->meta_key) && isset($userMeta->meta_value) && property_exists("UserBO", $userMeta->meta_key)) {
                    if (isset($userMeta->meta_key) && isset($userMeta->meta_value)) {
                        $meta_key = $userMeta->meta_key;
                        $this->$meta_key = $userMeta->meta_value;

                        if ($userMeta->meta_key == 'avatar_object' && is_object($userMeta->meta_value)) {
                            $image_metadataBO = $userMeta->meta_value;
                            if (isset($image_metadataBO->attachment_metadata) && isset($image_metadataBO->attachment_metadata->sizes)) {
                                if (isset($image_metadataBO->attachment_metadata->sizes->slider_thumb) && isset($image_metadataBO->attachment_metadata->sizes->slider_thumb->url)) {
                                    $this->avatar_url = $image_metadataBO->attachment_metadata->sizes->slider_thumb->url;
                                } elseif (isset($image_metadataBO->attachment_metadata->sizes->thumbnail) && isset($image_metadataBO->attachment_metadata->sizes->thumbnail->url)) {
                                    $this->avatar_url = $image_metadataBO->attachment_metadata->sizes->thumbnail->url;
                                } elseif (isset($image_metadataBO->attachment_metadata->sizes->post_thumbnail) && isset($image_metadataBO->attachment_metadata->sizes->post_thumbnail->url)) {
                                    $this->avatar_url = $image_metadataBO->attachment_metadata->sizes->post_thumbnail->url;
                                } elseif (isset($image_metadataBO->attachment_metadata->sizes->medium) && isset($image_metadataBO->attachment_metadata->sizes->medium->url)) {
                                    $this->avatar_url = $image_metadataBO->attachment_metadata->sizes->medium->url;
                                } elseif (isset($image_metadataBO->attachment_metadata->sizes->medium_large) && isset($image_metadataBO->attachment_metadata->sizes->medium_large->url)) {
                                    $this->avatar_url = $image_metadataBO->attachment_metadata->sizes->medium_large->url;
                                } elseif (isset($image_metadataBO->attachment_metadata->sizes->large) && isset($image_metadataBO->attachment_metadata->sizes->large->url)) {
                                    $this->avatar_url = $image_metadataBO->attachment_metadata->sizes->large->url;
                                } else {
                                    $this->avatar_url = $image_metadataBO->guid;
                                }
                            } else {
                                $this->avatar_url = $image_metadataBO->guid;
                            }
                        }

//                        if ($meta_key == 'session_tokens') {
//                            $a = unserialize($userMeta->meta_value);
////                            $c = $a['administrator'];
////                            $d = $a['subscriber'];
//                            $e = json_encode($a);
//                            $f = json_decode($e);
////                            $b = $a->administrator; //true
////                            $c = $a->subscriber; //null
//                            
//                            
//                        }
                    }
                }
            }
        }
    }
}

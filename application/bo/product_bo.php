<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class ProductBO extends PostBO
{

    public $post_type = "product";
    public $image_ids;
    public $image_urls;
    public $category_id;
    public $category_name;

    function __construct()
    {
        parent::__construct();
    }

    public function setProductInfo($productInfo)
    {
        if (!is_null($productInfo)) {
            if (isset($productInfo->image_ids)) {
                $this->image_ids = $productInfo->image_ids;                
            }
            if (isset($productInfo->image_urls)) {
                $this->image_urls = $productInfo->image_urls;                
            }
            if (isset($productInfo->images)) {
                $this->images = $productInfo->images;                
            }
        }
    }
}

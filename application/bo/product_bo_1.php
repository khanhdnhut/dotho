<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BO::autoloadBO("taxonomy");

class ProductBO extends TaxonomyBO
{

    public $taxonomy = "product";
    public $postBO;
    public $images;
    public $image_ids;
    public $tag_list;

}

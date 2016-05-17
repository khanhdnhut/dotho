<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BO::autoloadBO("taxonomy");

class CategoryBO extends TaxonomyBO
{

    public $taxonomy = "category";
    public $postBO;
    public $images;
    public $image_ids;
    public $tag_list;
    public $level;

}

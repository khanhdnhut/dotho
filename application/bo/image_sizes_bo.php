<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('thumbnail');
class Image_SizesBO extends BO 
{
    /** @var ThumbnailBO */
    public $slider_thumb;
    /** @var ThumbnailBO */
    public $thumbnail;
    /** @var ThumbnailBO */
    public $post_thumbnail;
    /** @var ThumbnailBO */
    public $medium;
    /** @var ThumbnailBO */
    public $medium_large;
    /** @var ThumbnailBO */
    public $large;

    function __construct()
    {
        
    }
}
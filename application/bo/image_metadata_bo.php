<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BO::autoloadBO('image_meta');
BO::autoloadBO('image_sizes');
class Image_MetadataBO extends BO
{
    public $width;
    public $height;
    public $file;
    /** @var Image_SizesBO */
    public $sizes;
    /** @var Image_MetaBO */
    public $image_meta;

    function __construct()
    {
        
    }
}
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ThumbnailBO extends BO
{

    public $file;
    public $width;
    public $height;
    public $mine_type;

    function __construct($file_name_thumb, $file_path)
    {
        parent::__construct();
        $this->file = $file_name_thumb;
        $this->url = $file_path;
        $this->mine_type = Utils::_mime_content_type($file_name_thumb);
        $imageThumbSizes = getimagesize($file_name_thumb);
        $this->width = $imageThumbSizes[0];
        $this->height = $imageThumbSizes[1];
    }
}

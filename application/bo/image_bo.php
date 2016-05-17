<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BO::autoloadBO('attachment');
BO::autoloadBO('image_metadata');
class ImageBO extends AttachmentBO
{
    /** @var Image_MetadataBO */
    public $attachment_metadata;

    function __construct()
    {
    }
    
    function decode_attachment_metadata()
    {
        if (!is_object($this->attachment_metadata)) {
            $this->attachment_metadata = json_decode($this->attachment_metadata);
        }
        
    }


}

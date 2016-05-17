<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class TermBO extends BO
{

    public $term_id = null;
    public $name = null;
    public $slug = null;
    public $term_group = null;

    public function setTermInfo($termInfo)
    {
        if (!is_null($termInfo)) {
            if (isset($termInfo->term_id)) {
                $this->term_id = $termInfo->term_id;
            }
            if (isset($termInfo->name)) {
                $this->name = $termInfo->name;
            }
            if (isset($termInfo->slug)) {
                $this->slug = $termInfo->slug;
            }
            if (isset($termInfo->term_group)) {
                $this->term_group = $termInfo->term_group;
            }
        }
    }

    public function setTermMetaInfo($termMetaInfoArray)
    {
        if (!is_null($termMetaInfoArray) && is_array($termMetaInfoArray) && count($termMetaInfoArray) > 0) {
            foreach ($termMetaInfoArray as $userMeta) {
                if (isset($userMeta->term_id) && $userMeta->term_id == $this->term_id) {
//                    if (isset($userMeta->meta_key) && isset($userMeta->meta_value) && property_exists("TermBO", $userMeta->meta_key)) {
                    if (isset($userMeta->meta_key) && isset($userMeta->meta_value)) {
                        $meta_key = $userMeta->meta_key;
                        $this->$meta_key = $userMeta->meta_value;
                    }
                }
            }
        }
    }
}


<?php

BO::autoloadBO("term");
class TaxonomyBO extends TermBO
{
    public $term_taxonomy_id = null;
    public $taxonomy = null;
    public $description = "";
    public $parent = 0;
    public $count = 0;

    public function setTaxonomyInfo($termInfo)
    {
        if (!is_null($termInfo)) {
            if (isset($termInfo->term_taxonomy_id)) {
                $this->term_taxonomy_id = $termInfo->term_taxonomy_id;
            }
            if (isset($termInfo->taxonomy)) {
                $this->taxonomy = $termInfo->taxonomy;
            }
            if (isset($termInfo->description)) {
                $this->description = $termInfo->description;
            }
            if (isset($termInfo->parent)) {
                $this->parent = $termInfo->parent;
            }
            if (isset($termInfo->count)) {
                $this->count = $termInfo->count;
            }
        }
    }
}

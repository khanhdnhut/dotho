<?php
Model::autoloadModel('term');

class TaxonomyModel extends TermModel
{

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function isExistName($name, $taxonomy)
    {
        $sth = $this->db->prepare("SELECT u." . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " " .
            " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_TERMS . " AS m " .
            " WHERE  m." . TB_TERMS_COL_NAME . " = :name "
            . " AND m." . TB_TERMS_COL_TERM_ID . " = u." . TB_TERM_TAXONOMY_COL_TERM_ID . " "
            . " AND u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy ");

        $sth->execute(array(':name' => $name, ':taxonomy' => $taxonomy));
        $count = $sth->rowCount();
        if ($count != 0) {
            $result = $sth->fetch();
            return $result->term_taxonomy_id;
        }
        return FALSE;
    }

    public function isExistSlug($slug, $taxonomy)
    {
        $sth = $this->db->prepare("SELECT u." . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " " .
            " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_TERMS . " AS m " .
            " WHERE  m." . TB_TERMS_COL_SLUG . " = :slug "
            . " AND m." . TB_TERMS_COL_TERM_ID . " = u." . TB_TERM_TAXONOMY_COL_TERM_ID . " "
            . " AND u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy ");

        $sth->execute(array(':slug' => $slug, ':taxonomy' => $taxonomy));
        $count = $sth->rowCount();
        if ($count != 0) {
            $result = $sth->fetch();
            return $result->term_taxonomy_id;
        }
        return FALSE;
    }

    public function update($taxonomyBO)
    {
        if (is_a($taxonomyBO, "TaxonomyBO")) {
            try {
                $sql = "UPDATE " . TABLE_TERM_TAXONOMY . " ";
                $set = "SET ";
                $where = " WHERE " . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id;";

                $para_array = [];
                $para_array[":term_taxonomy_id"] = $taxonomyBO->term_taxonomy_id;

                if (isset($taxonomyBO->count)) {
                    $set .= " " . TB_TERM_TAXONOMY_COL_COUNT . " = :count,";
                    $para_array[":count"] = $taxonomyBO->count;
                }
                if (isset($taxonomyBO->description)) {
                    $set .= " " . TB_TERM_TAXONOMY_COL_DESCRIPTION . " = :description,";
                    $para_array[":description"] = $taxonomyBO->description;
                }
                if (isset($taxonomyBO->parent)) {
                    $set .= " " . TB_TERM_TAXONOMY_COL_PARENT . " = :parent,";
                    $para_array[":parent"] = $taxonomyBO->parent;
                }

                if (count($para_array) != 0) {
                    $set = substr($set, 0, strlen($set) - 1);
                    $sql .= $set . $where;
                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);

                    parent::update($taxonomyBO);
                    return TRUE;
                }
            } catch (Exception $e) {
                
            }
        }

        return FALSE;
    }

    /**
     * addToDatabase
     *
     * Adds new Taxonomy to database
     *
     * @param TaxonomyBO $taxonomyBO new taxonomy
     */
    public function addToDatabase($taxonomyBO)
    {
        if (is_a($taxonomyBO, "TaxonomyBO")) {
            try {
                $term_id = parent::addToDatabase($taxonomyBO);
                if (!is_null($term_id)) {
                    try {
                        $sql = "insert into " . TABLE_TERM_TAXONOMY . " 
                            (" . TB_TERM_TAXONOMY_COL_COUNT . ",
                             " . TB_TERM_TAXONOMY_COL_DESCRIPTION . ",
                             " . TB_TERM_TAXONOMY_COL_PARENT . ",
                             " . TB_TERM_TAXONOMY_COL_TAXONOMY . ",
                             " . TB_TERM_TAXONOMY_COL_TERM_ID . ")
                values (:count,
                        :description,
                        :parent,
                        :taxonomy,
                        :term_id);";
                        $sth = $this->db->prepare($sql);

                        $sth->execute(array(
                            ":count" => $taxonomyBO->count,
                            ":description" => $taxonomyBO->description,
                            ":parent" => $taxonomyBO->parent,
                            ":taxonomy" => $taxonomyBO->taxonomy,
                            ":term_id" => $term_id
                        ));

                        $count = $sth->rowCount();
                        if ($count > 0) {
                            $taxonomy_id = $this->db->lastInsertId();
                            return $taxonomy_id;
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            } catch (Exception $e) {
                
            }
        }

        return NULL;
    }

    function getAllSorted($result, $tree, $level)
    {
        if ($tree != NULL) {
            if (!is_a($result, "SplDoublyLinkedList")) {
                $result = new SplDoublyLinkedList();
            }
            if (is_array($tree)) {
                foreach ($tree as $value) {
                    $this->getAllSorted($result, $value, $level + 1);
                }
            } else {
                if (isset($tree->name)) {
                    $tree->name = str_repeat("-", $level) . " " . $tree->name;
                }
                $result->push($tree);
                if (isset($tree->childs) && is_array($tree->childs)) {
                    $this->getAllSorted($result, $tree->childs, $level + 1);
                }
            }
        }
        return $result;
    }

    function buildTree($taxonomyList)
    {
        if ($taxonomyList != NULL) {
            $childs = array();

            foreach ($taxonomyList as &$taxonomy)
                $childs[$taxonomy->parent][] = &$taxonomy;
            unset($taxonomy);

            foreach ($taxonomyList as &$taxonomy)
                if (isset($childs[$taxonomy->term_taxonomy_id]))
                    $taxonomy->childs = $childs[$taxonomy->term_taxonomy_id];
            if (count($childs) > 0 && isset($childs[0])) {
                return $childs[0];
            }
        }
        return NULL;
    }

    public function getAll($taxonomy)
    {
        try {
            $sth = $this->db->prepare("SELECT u.term_taxonomy_id, u.term_id, "
                . " u.taxonomy, u.description, u.parent, u.count, m.name, m.slug, m.term_group "
                . " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_TERMS . " AS m "
                . " WHERE  u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy "
                . " AND u." . TB_TERM_TAXONOMY_COL_TERM_ID . " = m." . TB_TERMS_COL_TERM_ID . " ");

            $sth->execute(array(':taxonomy' => $taxonomy));
            $count = $sth->rowCount();
            if ($count != 0) {
                $taxonomyList = $sth->fetchAll();
                for ($i = 0; $i < sizeof($taxonomyList); $i++) {
                    $taxonomyInfo = $taxonomyList[$i];
                    $this->autoloadBO('taxonomy');
                    $taxonomyBO = new TaxonomyBO();
                    $taxonomyBO->setTaxonomyInfo($taxonomyInfo);
                    $taxonomyBO->setTermInfo($taxonomyInfo);
                    $taxonomyBO->setTermMetaInfo($this->getMetaInfo($taxonomyBO->term_id));
                    $taxonomyList[$i] = $taxonomyBO;
                }
                return $taxonomyList;
            }
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function get($taxonomy_id)
    {
        try {
            $sth = $this->db->prepare("SELECT u.term_taxonomy_id, u.term_id, "
                . " u.taxonomy, u.description, u.parent, u.count, m.name, m.slug, m.term_group "
                . " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_TERMS . " AS m "
                . " WHERE  u." . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id "
                . " AND u." . TB_TERM_TAXONOMY_COL_TERM_ID . " = m." . TB_TERMS_COL_TERM_ID . " ");


            $sth->execute(array(':term_taxonomy_id' => $taxonomy_id));
            $count = $sth->rowCount();
            if ($count != 1) {
                return null;
            }
            $result = $sth->fetch();

            $this->autoloadBO('taxonomy');
            $taxonomyBO = new TaxonomyBO();
            $taxonomyBO->setTaxonomyInfo($result);
            $taxonomyBO->setTermInfo($result);
            $termMetaInfoArray = $this->getMetaInfo($result->term_id);
            $taxonomyBO->setTermMetaInfo($termMetaInfoArray);
            return $taxonomyBO;
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function getByMetaData($taxonomy, $meta_key, $meta_value)
    {
        try {
            $sth = $this->db->prepare("SELECT u.term_taxonomy_id, u.term_id, "
                . " u.taxonomy, u.description, u.parent, u.count, m.name, m.slug, m.term_group "
                . " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_TERMS . " AS m, " . TABLE_TERMMETA . " AS n "
                . " WHERE  u." . TB_TERM_TAXONOMY_COL_TERM_ID . " = m." . TB_TERMS_COL_TERM_ID . " "
                . " AND m." . TB_TERMS_COL_TERM_ID . " = n." . TB_TERMMETA_COL_TERM_ID . " "
                . " AND u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy "
                . " AND n." . TB_TERMMETA_COL_META_KEY . " = :meta_key "
                . " AND n." . TB_TERMMETA_COL_META_VALUE . " = :meta_value "
            );

            $sth->execute(array(':taxonomy' => $taxonomy, ':meta_key' => $meta_key, ':meta_value' => $meta_value));
            $count = $sth->rowCount();
            if ($count == 0) {
                return null;
            }

            $taxonomyList = $sth->fetchAll();
            for ($i = 0; $i < sizeof($taxonomyList); $i++) {
                $taxonomyInfo = $taxonomyList[$i];
                $this->autoloadBO('taxonomy');
                $taxonomyBO = new TaxonomyBO();
                $taxonomyBO->setTaxonomyInfo($taxonomyInfo);
                $taxonomyBO->setTermInfo($taxonomyInfo);
                $taxonomyBO->setTermMetaInfo($this->getMetaInfo($taxonomyBO->term_id));
                $taxonomyList[$i] = $taxonomyBO;
            }
            return $taxonomyList;
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function getTaxonomyRelationshipByObjectId($object_id, $taxonomy)
    {
        try {
            $sth = $this->db->prepare("SELECT u.term_taxonomy_id, u.term_id, "
                . " u.taxonomy, u.description, u.parent, u.count, m.name, m.slug, m.term_group "
                . " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_TERMS . " AS m, " . TABLE_TERM_RELATIONSHIPS . " as r "
                . " WHERE  u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy "
                . " AND u." . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = r." . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " "
                . " AND u." . TB_TERM_TAXONOMY_COL_TERM_ID . " = m." . TB_TERMS_COL_TERM_ID . " "
                . " AND r." . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . " = :object_id");

            $sth->execute(array(':taxonomy' => $taxonomy, ':object_id' => $object_id));
            $count = $sth->rowCount();
            if ($count != 0) {
                $taxonomyList = $sth->fetchAll();
                for ($i = 0; $i < sizeof($taxonomyList); $i++) {
                    $taxonomyInfo = $taxonomyList[$i];
                    $this->autoloadBO('taxonomy');
                    $taxonomyBO = new TaxonomyBO();
                    $taxonomyBO->setTaxonomyInfo($taxonomyInfo);
                    $taxonomyBO->setTermInfo($taxonomyInfo);
                    $taxonomyBO->setTermMetaInfo($this->getMetaInfo($taxonomyBO->term_id));
                    $taxonomyList[$i] = $taxonomyBO;
                }
                return $taxonomyList;
            }
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function delete($taxonomy_id)
    {
        $termBO = $this->get($taxonomy_id);

        try {
            $sth = $this->db->prepare("DELETE
FROM " . TABLE_TERM_TAXONOMY . "
WHERE " . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id");
            $sth->execute(array(':term_taxonomy_id' => $taxonomy_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                parent::delete($termBO->term_id);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function deleteRelationship($post_id, $taxonomy_id)
    {
        $termBO = $this->get($taxonomy_id);

        try {
            $sth = $this->db->prepare("DELETE "
                . " FROM " . TABLE_TERM_RELATIONSHIPS
                . " WHERE " . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id "
                . " AND " . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . " = :post_id; ");
            $sth->execute(array(':term_taxonomy_id' => $taxonomy_id, ':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function search($view, $para)
    {
        try {
            $paraSQL = [];
            $sqlSelectAll = "SELECT u.term_taxonomy_id, u.term_id, u.taxonomy, u.description, u.parent, u.count, m.name, m.slug, m.term_group ";
            $sqlSelectCount = "SELECT COUNT(*) as countTaxonomy ";
            //para: orderby, order, page, s, paged, countries, new_role, new_role2, action, action2
            $sqlFrom = " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_TERMS . " AS m ";
            $sqlWhere = " WHERE m." . TB_TERMS_COL_TERM_ID . " = u." . TB_TERM_TAXONOMY_COL_TERM_ID . " AND u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy ";

            $paraSQL[':taxonomy'] = $view->taxonomy;
            if (isset($para->s) && strlen(trim($para->s)) > 0) {
                $sqlWhere .= " AND (u." . TB_TERM_TAXONOMY_COL_DESCRIPTION . " like :s OR
m." . TB_TERMS_COL_SLUG . " like :s OR
m." . TB_TERMS_COL_NAME . " like :s ) ";
                $paraSQL[':s'] = "%" . $para->s . "%";
                $view->s = $para->s;
            }

            $view->orderby = "name";
            $view->order = "asc";

            if (isset($para->orderby) && in_array($para->orderby, array("description", "name", "slug", "count"))) {
                switch ($para->orderby) {
                    case "description":
                        $para->orderby = TB_TERM_TAXONOMY_COL_DESCRIPTION;
                        $view->orderby = "description";
                        break;
                    case "name":
                        $para->orderby = TB_TERMS_COL_NAME;
                        $view->orderby = "name";
                        break;
                    case "count":
                        $para->orderby = TB_TERM_TAXONOMY_COL_COUNT;
                        $view->orderby = "count";
                        break;
                    case "slug":
                        $para->orderby = TB_TERMS_COL_SLUG;
                        $view->orderby = "slug";
                        break;
                }

                if (isset($para->order) && in_array($para->order, array("desc", "asc"))) {
                    $view->order = $para->order;
                } else {
                    $para->order = "asc";
                    $view->order = "asc";
                }
                $sqlOrderby = " ORDER BY " . $para->orderby . " " . $para->order;
            } else {
                $sqlOrderby = " ORDER BY " . TB_TERMS_COL_NAME . " ASC";
            }

            $sqlCount = $sqlSelectCount . $sqlFrom . $sqlWhere;
            $sth = $this->db->prepare($sqlCount);
            $sth->execute($paraSQL);
            $countTaxonomy = (int) $sth->fetch()->countTaxonomy;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countTaxonomy > 0) {
                $view->count = $countTaxonomy;

                $view->pageNumber = floor($view->count / $view->taxonomies_per_page);
                if ($view->count % $view->taxonomies_per_page != 0) {
                    $view->pageNumber++;
                }

                if (isset($para->page)) {
                    try {
                        $page = (int) $para->page;
                        if ($para->page <= 0) {
                            $page = 1;
                        }
                    } catch (Exception $e) {
                        $page = 1;
                    }
                } else {
                    $page = 1;
                }
                if ($page > $view->pageNumber) {
                    $page = $view->pageNumber;
                }

                $view->page = $page;
                $startTaxonomy = ($page - 1) * $view->taxonomies_per_page;
                $sqlLimit = " LIMIT " . $view->taxonomies_per_page . " OFFSET " . $startTaxonomy;

                $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                $sth = $this->db->prepare($sqlAll);
                $sth->execute($paraSQL);
                $count = $sth->rowCount();
                if ($count > 0) {
                    $taxonomyList = $sth->fetchAll();
                    for ($i = 0; $i < sizeof($taxonomyList); $i++) {
                        $taxonomyInfo = $taxonomyList[$i];
                        $this->autoloadBO('taxonomy');
                        $taxonomyBO = new TaxonomyBO();
                        $taxonomyBO->setTaxonomyInfo($taxonomyInfo);
                        $taxonomyBO->setTermInfo($taxonomyInfo);
                        $taxonomyBO->setTermMetaInfo($this->getMetaInfo($taxonomyBO->term_id));
                        $taxonomyList[$i] = $taxonomyBO;
                    }
                    $view->taxonomyList = $taxonomyList;
                } else {
                    $view->taxonomyList = NULL;
                }
            } else {
                $view->taxonomyList = NULL;
            }
        } catch (Exception $e) {
            $view->taxonomyList = NULL;
        }
    }

    public function addRelationshipToDatabase($post_id, $term_taxonomy_id, $term_order = 0)
    {
        try {
            $sql = "insert into " . TABLE_TERM_RELATIONSHIPS . " 
                            (" . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . ",
                             " . TB_TERM_RELATIONSHIPS_COL_TERM_ORDER . ",
                             " . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . ")
                values (:object_id,
                        :term_order,
                        :term_taxonomy_id);";
            $sth = $this->db->prepare($sql);

            $sth->execute(array(
                ":object_id" => $post_id,
                ":term_order" => $term_order,
                ":term_taxonomy_id" => $term_taxonomy_id
            ));

            $count = $sth->rowCount();
            if ($count > 0) {
                $post_id = $this->db->lastInsertId();
                return $post_id;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }
}

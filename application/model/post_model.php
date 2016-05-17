<?php

class PostModel extends Model
{

    public function addMetaInfoToDatabase($post_id, $meta_key, $meta_value)
    {
        try {
            $sql2 = "insert into " . TABLE_POSTMETA . " 
                            (" . TB_POSTMETA_COL_POST_ID . ",
                             " . TB_POSTMETA_COL_META_KEY . ",
                             " . TB_POSTMETA_COL_META_VALUE . ")
                values (:post_id,
                        :meta_key,
                        :meta_value);";
            $sth2 = $this->db->prepare($sql2);

            $sth2->execute(array(
                ":post_id" => $post_id,
                ":meta_key" => $meta_key,
                ":meta_value" => $meta_value
            ));
            $count2 = $sth2->rowCount();
            if ($count2 > 0) {
                $postMeta_id = $this->db->lastInsertId();
                return $postMeta_id;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function updateMetaInfoToDatabase($post_id, $meta_key, $meta_value)
    {
        try {
            $sql = "UPDATE " . TABLE_POSTMETA . " 
                    SET " . TB_POSTMETA_COL_META_VALUE . " = :meta_value
                    WHERE " . TB_POSTMETA_COL_POST_ID . " = :post_id AND " . TB_POSTMETA_COL_META_KEY . " = :meta_key";
            $sth = $this->db->prepare($sql);
            $sth->execute(array(':meta_key' => $meta_key, ':meta_value' => $meta_value, ':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count == 0) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * addToDatabase
     *
     * Adds new post to database
     *
     * @param PostBO $postBO new post
     */
    public function addToDatabase($postBO)
    {
        try {
            $sql = "insert into " . TABLE_POSTS . " 
                            (" . TB_POST_COL_POST_AUTHOR . ",
                             " . TB_POST_COL_POST_DATE . ",
                             " . TB_POST_COL_POST_DATE_GMT . ",
                             " . TB_POST_COL_POST_CONTENT . ",
                             " . TB_POST_COL_POST_TITLE . ",
                             " . TB_POST_COL_POST_EXCERPT . ",
                             " . TB_POST_COL_POST_STATUS . ",
                             " . TB_POST_COL_COMMENT_STATUS . ",
                             " . TB_POST_COL_PING_STATUS . ",
                             " . TB_POST_COL_POST_PASSWORD . ",
                             " . TB_POST_COL_POST_NAME . ",
                             " . TB_POST_COL_TO_PING . ",
                             " . TB_POST_COL_PINGED . ",
                             " . TB_POST_COL_POST_MODIFIED . ",
                             " . TB_POST_COL_POST_MODIFIED_GMT . ",
                             " . TB_POST_COL_POST_CONTENT_FILTERED . ",
                            " . TB_POST_COL_POST_PARENT . ",
                             " . TB_POST_COL_GUID . ",
                             " . TB_POST_COL_MENU_ORDER . ",
                             " . TB_POST_COL_POST_TYPE . ",
                             " . TB_POST_COL_POST_MIME_TYPE . ",
                             " . TB_POST_COL_COMMENT_COUNT . ")
                values (:post_author,
                        :post_date,
                        :post_date_gmt,
                        :post_content,
                        :post_title,
                        :post_excerpt,
                        :post_status,
                        :comment_status,
                        :ping_status,
                        :post_password,
                        :post_name,
                        :to_ping,
                        :pinged,
                        :post_modified,
                        :post_modified_gmt,
                        :post_content_filtered,
                        :post_parent,
                        :guid,
                        :menu_order,
                        :post_type,
                        :post_mime_type,
                        :comment_count);";
            $sth = $this->db->prepare($sql);

            $sth->execute(array(
                ":post_author" => $postBO->post_author,
                ":post_date" => $postBO->post_date,
                ":post_date_gmt" => $postBO->post_date_gmt,
                ":post_content" => $postBO->post_content,
                ":post_title" => $postBO->post_title,
                ":post_excerpt" => $postBO->post_excerpt,
                ":post_status" => $postBO->post_status,
                ":comment_status" => $postBO->comment_status,
                ":ping_status" => $postBO->ping_status,
                ":post_password" => $postBO->post_password,
                ":post_name" => $postBO->post_name,
                ":to_ping" => $postBO->to_ping,
                ":pinged" => $postBO->pinged,
                ":post_modified" => $postBO->post_modified,
                ":post_modified_gmt" => $postBO->post_modified_gmt,
                ":post_content_filtered" => $postBO->post_content_filtered,
                ":post_parent" => $postBO->post_parent,
                ":guid" => $postBO->guid,
                ":menu_order" => $postBO->menu_order,
                ":post_type" => $postBO->post_type,
                ":post_mime_type" => $postBO->post_mime_type,
                ":comment_count" => $postBO->comment_count
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

    public function updateRelationship($post_id, $term_taxonomy_id_old, $term_taxonomy_id_new, $term_order = 0)
    {
        try {
            $sql = "UPDATE " . TABLE_TERM_RELATIONSHIPS . " 
                    SET " . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id_new, " . TB_TERM_RELATIONSHIPS_COL_TERM_ORDER . " = :term_order 
                    WHERE " . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . " = :object_id AND " . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id_old";
            $sth = $this->db->prepare($sql);
            $sth->execute(array(':term_order' => $term_order, ':term_taxonomy_id_old' => $term_taxonomy_id_old, ':term_taxonomy_id_new' => $term_taxonomy_id_new, ':object_id' => $post_id,));
            $count = $sth->rowCount();
            if ($count != 1) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * update
     *
     * Update info of post
     *
     * @param PostBO $postBO post
     */
    public function update($postBO)
    {
        if (is_a($postBO, "PostBO")) {
            try {
                $sql = "UPDATE " . TABLE_POSTS . " ";
                $set = "SET ";
                $where = " WHERE " . TB_POST_COL_ID . " = :post_id;";

                $para_array = [];
                $para_array[":post_id"] = $postBO->ID;

                if (isset($postBO->comment_count)) {
                    $set .= " " . TB_POST_COL_COMMENT_COUNT . " = :comment_count,";
                    $para_array[":comment_count"] = $postBO->comment_count;
                }
                if (isset($postBO->comment_status)) {
                    $set .= " " . TB_POST_COL_COMMENT_STATUS . " = :comment_status,";
                    $para_array[":comment_status"] = $postBO->comment_status;
                }
                if (isset($postBO->guid)) {
                    $set .= " " . TB_POST_COL_GUID . " = :guid,";
                    $para_array[":guid"] = $postBO->guid;
                }
                if (isset($postBO->menu_order)) {
                    $set .= " " . TB_POST_COL_MENU_ORDER . " = :menu_order,";
                    $para_array[":menu_order"] = $postBO->menu_order;
                }
                if (isset($postBO->ping_status)) {
                    $set .= " " . TB_POST_COL_PING_STATUS . " = :ping_status,";
                    $para_array[":ping_status"] = $postBO->ping_status;
                }

                if (isset($postBO->pinged)) {
                    $set .= " " . TB_POST_COL_PINGED . " = :pinged,";
                    $para_array[":pinged"] = $postBO->pinged;
                }
                if (isset($postBO->post_author)) {
                    $set .= " " . TB_POST_COL_POST_AUTHOR . " = :post_author,";
                    $para_array[":post_author"] = $postBO->post_author;
                }
                if (isset($postBO->post_content)) {
                    $set .= " " . TB_POST_COL_POST_CONTENT . " = :post_content,";
                    $para_array[":post_content"] = $postBO->post_content;
                }
                if (isset($postBO->post_content_filtered)) {
                    $set .= " " . TB_POST_COL_POST_CONTENT_FILTERED . " = :post_content_filtered,";
                    $para_array[":post_content_filtered"] = $postBO->post_content_filtered;
                }
                if (isset($postBO->post_date)) {
                    $set .= " " . TB_POST_COL_POST_DATE . " = :post_date,";
                    $para_array[":post_date"] = $postBO->post_date;
                }

                if (isset($postBO->post_date_gmt)) {
                    $set .= " " . TB_POST_COL_POST_DATE_GMT . " = :post_date_gmt,";
                    $para_array[":post_date_gmt"] = $postBO->post_date_gmt;
                }
                if (isset($postBO->post_excerpt)) {
                    $set .= " " . TB_POST_COL_POST_EXCERPT . " = :post_excerpt,";
                    $para_array[":post_excerpt"] = $postBO->post_excerpt;
                }
                if (isset($postBO->post_mime_type)) {
                    $set .= " " . TB_POST_COL_POST_MIME_TYPE . " = :post_mime_type,";
                    $para_array[":post_mime_type"] = $postBO->post_mime_type;
                }
                if (isset($postBO->post_modified)) {
                    $set .= " " . TB_POST_COL_POST_MODIFIED . " = :post_modified,";
                    $para_array[":post_modified"] = $postBO->post_modified;
                }
                if (isset($postBO->post_modified_gmt)) {
                    $set .= " " . TB_POST_COL_POST_MODIFIED_GMT . " = :post_modified_gmt,";
                    $para_array[":post_modified_gmt"] = $postBO->post_modified_gmt;
                }

                if (isset($postBO->post_name)) {
                    $set .= " " . TB_POST_COL_POST_NAME . " = :post_name,";
                    $para_array[":post_name"] = $postBO->post_name;
                }
                if (isset($postBO->post_parent)) {
                    $set .= " " . TB_POST_COL_POST_PARENT . " = :post_parent,";
                    $para_array[":post_parent"] = $postBO->post_parent;
                }
                if (isset($postBO->post_password)) {
                    $set .= " " . TB_POST_COL_POST_PASSWORD . " = :post_password,";
                    $para_array[":post_password"] = $postBO->post_password;
                }
                if (isset($postBO->post_status)) {
                    $set .= " " . TB_POST_COL_POST_STATUS . " = :post_status,";
                    $para_array[":post_status"] = $postBO->post_status;
                }
                if (isset($postBO->post_title)) {
                    $set .= " " . TB_POST_COL_POST_TITLE . " = :post_title,";
                    $para_array[":post_title"] = $postBO->post_title;
                }
                if (isset($postBO->post_type)) {
                    $set .= " " . TB_POST_COL_POST_TYPE . " = :post_type,";
                    $para_array[":post_type"] = $postBO->post_type;
                }
                if (isset($postBO->to_ping)) {
                    $set .= " " . TB_POST_COL_TO_PING . " = :to_ping,";
                    $para_array[":to_ping"] = $postBO->to_ping;
                }

                if (count($para_array) != 0) {
                    $set = substr($set, 0, strlen($set) - 1);
                    $sql .= $set . $where;
                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);

                    return TRUE;
                }
            } catch (Exception $e) {
                
            }
        }

        return FALSE;
    }

    public function getPostRelationshipByTaxonomyId($term_taxonomy_id, $taxonomy)
    {
        try {
            $sth = $this->db->prepare("SELECT m.* "
                . " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_POSTS . " AS m, " . TABLE_TERM_RELATIONSHIPS . " as r "
                . " WHERE  u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy "
                . " AND u." . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = r." . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " "
                . " AND r." . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . " =  m." . TB_POST_COL_ID . " "
                . " AND r." . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id ");

            $sth->execute(array(':taxonomy' => $taxonomy, ':term_taxonomy_id' => $term_taxonomy_id));
            $count = $sth->rowCount();
            if ($count != 0) {
                $postList = $sth->fetchAll();
                for ($i = 0; $i < sizeof($postList); $i++) {
                    $postInfo = $postList[$i];
                    $this->autoloadBO('post');
                    $postBO = new PostBO();
                    $postBO->setPost($postInfo);
                    $postMetaInfoArray = $this->getMetaInfo($postInfo->ID);
                    $postBO->setPostMetaInfo($postMetaInfoArray);
                    $postList[$i] = $postBO;
                }
                return $postList;
            }
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function getPostRelationshipByTaxonomyIdArray($term_taxonomy_id_array, $post_id_ignore_array, $taxonomy, $page, $number_results_per_page)
    {
        if ($term_taxonomy_id_array != null && is_array($term_taxonomy_id_array) && count($term_taxonomy_id_array) > 0) {
            $array = implode(",", $term_taxonomy_id_array);
            if ($post_id_ignore_array != null && is_array($post_id_ignore_array) && count($post_id_ignore_array) > 0) {
                $array_post_id_ignore = implode(",", $post_id_ignore_array);
            } else {
                $array_post_id_ignore = "";
            }
            try {
                $sql = "SELECT m.* "
                    . " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_POSTS . " AS m, " . TABLE_TERM_RELATIONSHIPS . " as r "
                    . " WHERE  u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy "
                    . " AND u." . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = r." . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " "
                    . " AND r." . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . " =  m." . TB_POST_COL_ID . " "
                    . " AND r." . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " IN (" . $array . ") ";
                if ($array_post_id_ignore != "") {
                    $sql .= " AND m." . TB_POST_COL_ID . " NOT IN (" . $array_post_id_ignore . ") ";
                }
                $sql.= " LIMIT " . $number_results_per_page . " OFFSET " . (($page - 1) * $number_results_per_page);
                $sth = $this->db->prepare($sql);

                $sth->execute(array(':taxonomy' => $taxonomy));
                $count = $sth->rowCount();
                if ($count != 0) {
                    $postList = $sth->fetchAll();
                    for ($i = 0; $i < sizeof($postList); $i++) {
                        $postInfo = $postList[$i];
                        $this->autoloadBO('post');
                        $postBO = new PostBO();
                        $postBO->setPost($postInfo);
                        $postMetaInfoArray = $this->getMetaInfo($postInfo->ID);
                        $postBO->setPostMetaInfo($postMetaInfoArray);
                        $postList[$i] = $postBO;
                    }
                    return $postList;
                }
            } catch (Exception $e) {
                
            }
        }

        return null;
    }

    public function countPostRelationshipByTaxonomyIdArray($term_taxonomy_id_array, $post_id_ignore_array, $taxonomy)
    {
        if ($term_taxonomy_id_array != null && is_array($term_taxonomy_id_array) && count($term_taxonomy_id_array) > 0) {
            $array = implode(",", $term_taxonomy_id_array);
            if ($post_id_ignore_array != null && is_array($post_id_ignore_array) && count($post_id_ignore_array) > 0) {
                $array_post_id_ignore = implode(",", $post_id_ignore_array);
            } else {
                $array_post_id_ignore = "";
            }
            try {
                $sql = "SELECT count(*) as countPost  "
                    . " FROM " . TABLE_TERM_TAXONOMY . " AS u, " . TABLE_POSTS . " AS m, " . TABLE_TERM_RELATIONSHIPS . " as r "
                    . " WHERE  u." . TB_TERM_TAXONOMY_COL_TAXONOMY . " = :taxonomy "
                    . " AND u." . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = r." . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " "
                    . " AND r." . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . " =  m." . TB_POST_COL_ID . " "
                    . " AND r." . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " IN (" . $array . ") ";
                if ($array_post_id_ignore != "") {
                    $sql .= " AND m." . TB_POST_COL_ID . " NOT IN (" . $array_post_id_ignore . ") ";
                }

                $sth = $this->db->prepare($sql);

                $sth->execute(array(':taxonomy' => $taxonomy));
                $count = $sth->rowCount();
                if ($count != 0) {
                    $countPost = (int) $sth->fetch()->countPost;

                    return $countPost;
                }
            } catch (Exception $e) {
                
            }
        }

        return 0;
    }

    public function get($post_id)
    {
        try {
            $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_POSTS . "
                                   WHERE  " . TB_POST_COL_ID . " = :post_id");

            $sth->execute(array(':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count != 1) {
                return null;
            }
            // fetch one row (we only have one result)
            $result = $sth->fetch();
            $this->autoloadBO('post');
            $postBO = new PostBO();
            $postBO->setPost($result);
            $postMetaInfoArray = $this->getMetaInfo($result->ID);
            $postBO->setPostMetaInfo($postMetaInfoArray);
            return $postBO;
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function getMetaInfo($post_id)
    {
        try {
            $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_POSTMETA . "
                                   WHERE  " . TB_POSTMETA_COL_POST_ID . " = :post_id");

            $sth->execute(array(':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                return $sth->fetchAll();
            } else {
                return null;
            }
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function delete($post_id)
    {
        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_POSTS . "
                                   WHERE  " . TB_POST_COL_ID . " = :post_id");
            $sth->execute(array(':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                $this->deleteMeta($post_id);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function deleteMeta($post_id)
    {
        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_POSTMETA . "
                                   WHERE  " . TB_POSTMETA_COL_POST_ID . " = :post_id");
            $sth->execute(array(':post_id' => $post_id));
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

    public function deleteRelationship($post_id, $term_taxonomy_id)
    {
        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_TERM_RELATIONSHIPS . "
                                   WHERE  " . TB_TERM_RELATIONSHIPS_COL_OBJECT_ID . " = :post_id 
                                   AND " . TB_TERM_RELATIONSHIPS_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id ");
            $sth->execute(array(':post_id' => $post_id, ':term_taxonomy_id' => $term_taxonomy_id));
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

    public function isExistTitle($title, $post_type)
    {
        $sth = $this->db->prepare("SELECT u." . TB_POST_COL_ID . " " .
            " FROM " . TABLE_POSTS . " AS u " .
            " WHERE  u." . TB_POST_COL_POST_TITLE . " = :title "
            . " AND u." . TB_POST_COL_POST_TYPE . " = :post_type ");

        $sth->execute(array(':title' => $title, ':post_type' => $post_type));
        $count = $sth->rowCount();
        if ($count != 0) {
            return true;
        }
        return false;
    }

    public function isExistName($name, $post_type)
    {
        $sth = $this->db->prepare("SELECT u." . TB_POST_COL_ID . " " .
            " FROM " . TABLE_POSTS . " AS u " .
            " WHERE  u." . TB_POST_COL_POST_NAME . " = :name "
            . " AND u." . TB_POST_COL_POST_TYPE . " = :post_type ");

        $sth->execute(array(':name' => $name, ':post_type' => $post_type));
        $count = $sth->rowCount();
        if ($count != 0) {
            return true;
        }
        return false;
    }

    public function updateGuid($post_id, $guid)
    {
        try {
            $sql = "UPDATE " . TABLE_POSTS . " 
                    SET " . TB_POST_COL_GUID . " = :guid
                    WHERE " . TB_POST_COL_ID . " = :post_id";
            $sth = $this->db->prepare($sql);
            $sth->execute(array(':guid' => $guid, ':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count != 1) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}

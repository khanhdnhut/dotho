<?php
Model::autoloadModel('post');

class AttachmentModel extends PostModel
{

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * addToDatabase
     *
     * Adds new attachment to database
     *
     * @param AttachmentBO $attachmentInfo new attachment
     */
    public function addToDatabase($attachmentInfo)
    {
        try {
            $image_id = parent::addToDatabase($attachmentInfo);
            if (!is_null($image_id)) {
                if (isset($attachmentInfo->attachment_metadata)) {
                    $attachment_metadata = json_encode($attachmentInfo->attachment_metadata);
                    $this->addMetaInfoToDatabase($image_id, 'attachment_metadata', $attachment_metadata);
                }
                if (isset($attachmentInfo->attached_file)) {
                    $this->addMetaInfoToDatabase($image_id, 'attached_file', $attachmentInfo->attached_file);
                }
            }
            return $image_id;
        } catch (Exception $e) {
            
        }
        return NULL;
    }
    
    /**
     * get
     *
     * Get info of attachment by id
     *
     * @param int $attachment_id ID of attachment
     */
    public function get($attachment_id)
    {
        $attachmentBO = parent::get($attachment_id);
        if (isset($attachmentBO->attachment_metadata) && !is_object($attachmentBO->attachment_metadata)) {
            $attachmentBO->attachment_metadata = json_decode($attachmentBO->attachment_metadata);
        }
        return $attachmentBO;
    }

    
    /**
     * delete
     *
     * Delete attachment by id
     *
     * @param int $attachment_id ID of attachment
     */
    public function delete($attachment_id)
    {
        try {
            $postBO = $this->get($attachment_id);
            if (isset($postBO->attached_file) && $postBO->attached_file != "") {
                Utils::deleteFile($postBO->attached_file);
            }
            if (parent::delete($attachment_id)) {
                return TRUE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }
}

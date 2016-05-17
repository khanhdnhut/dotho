<?php
Model::autoloadModel('attachment');

class ImageModel extends AttachmentModel
{

    public $is_create_thumb = false;
    public $is_slider_thumb = false;
    public $slider_thumb_height;
    public $slider_thumb_width;
    public $slider_thumb_crop = true;
    public $slider_thumb_quality = 100;
    public $is_thumbnail = false;
    public $thumbnail_height;
    public $thumbnail_width;
    public $thumbnail_crop = true;
    public $thumbnail_quality = 100;
    public $is_post_thumbnail = false;
    public $post_thumbnail_height;
    public $post_thumbnail_width;
    public $post_thumbnail_crop = true;
    public $post_thumbnail_quality = 100;
    public $is_medium = false;
    public $medium_height;
    public $medium_width;
    public $medium_crop = true;
    public $medium_quality = 100;
    public $is_medium_large = false;
    public $medium_large_height;
    public $medium_large_width;
    public $medium_large_crop = true;
    public $medium_large_quality = 100;
    public $is_large = false;
    public $large_height;
    public $large_width;
    public $large_crop = true;
    public $large_quality = 100;

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function createThumbnail(Image_MetadataBO $attachment_metadata, $file_ori, $thumb_type, $width_ori, $height_ori, $width_new, $height_new, $folder, $path, $name, $extention, $crop = true, $quality = 100)
    {
        $file_name_thumb = $folder . $name . "-" . $width_new . "x" . round($height_new) . "." . $extention;
        $file_path = $path . $name . "-" . $width_new . "x" . round($height_new) . "." . $extention;
        if ($this->resize_image($file_ori, $file_name_thumb, $width_new, $height_new, $crop, $quality)) {
            $attachment_metadata->sizes->$thumb_type = new ThumbnailBO($file_name_thumb, $file_path);
        }
    }

    public function getImagesByArray($image_ids)
    {
        $images = array();
        if ($image_ids != null && is_array($image_ids) && count($image_ids) > 0) {
            foreach ($image_ids as $image_id) {
                $image_object = $this->get($image_id);
                if ($image_object != NULL) {
                    $image_info = new stdClass();
                    $image_info->image_id = $image_id;
                    if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {

                        if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                            $image_info->slider_thumb_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                        }
                        if (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                            $image_info->thumbnail_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                        }
                        if (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                            $image_info->post_thumbnail_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                        }
                        if (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                            $image_info->medium_url = $image_object->attachment_metadata->sizes->medium->url;
                        }
                        if (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                            $image_info->medium_large_url = $image_object->attachment_metadata->sizes->medium_large->url;
                        }
                        if (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                            $image_info->large_url = $image_object->attachment_metadata->sizes->large->url;
                        }
                    }
                    $image_info->image_url = $image_object->guid;
                    if (!isset($image_info->slider_thumb_url)) {
                        $image_info->slider_thumb_url = $image_object->guid;
                    }
                    if (!isset($image_info->thumbnail_url)) {
                        $image_info->thumbnail_url = $image_object->guid;
                    }
                    if (!isset($image_info->post_thumbnail_url)) {
                        $image_info->post_thumbnail_url = $image_object->guid;
                    }
                    if (!isset($image_info->medium_url)) {
                        $image_info->medium_url = $image_object->guid;
                    }
                    if (!isset($image_info->medium_large_url)) {
                        $image_info->medium_large_url = $image_object->guid;
                    }
                    if (!isset($image_info->large_url)) {
                        $image_info->large_url = $image_object->guid;
                    }
                    $images[] = $image_info;
                }
            }
        }
        return $images;        
    }

    public function uploadImage($name, $tmp_name)
    {
        $this->autoloadBO('image');
        $imageInfo = new ImageBO();
        $imageSizes = getimagesize($tmp_name);
        $attachment_metadata = new Image_MetadataBO();
        $attachment_metadata->width = $imageSizes[0];
        $attachment_metadata->height = $imageSizes[1];
        $image_meta = new Image_MetaBO();
        $image_meta->aperture = 0;
        $image_meta->credit = "";
        $image_meta->camera = "";
        $image_meta->caption = "";
        $image_meta->created_timestamp = 0;
        $image_meta->copyright = "";
        $image_meta->focal_length = 0;
        $image_meta->iso = 0;
        $image_meta->shutter_speed = 0;
        $image_meta->title = "";
        $image_meta->orientation = 1;
        $image_meta->keywords = "";
        $attachment_metadata->image_meta = $image_meta;

        $imageInfo->comment_count = 0;
        $imageInfo->comment_status = "open";

        $imageInfo->menu_order = 0;
        $imageInfo->ping_status = "open";
        $imageInfo->pinged = "";
        $imageInfo->post_author = Session::get("user_id");
        $imageInfo->post_content = $name;
        $imageInfo->post_content_filtered = "";
        $imageInfo->post_date = date("Y-m-d H:i:s");
        $imageInfo->post_date_gmt = gmdate("Y-m-d H:i:s");
        $imageInfo->post_excerpt = "";
        $imageInfo->post_mime_type = Utils::_mime_content_type($name);
        $imageInfo->post_modified = $imageInfo->post_date;
        $imageInfo->post_modified_gmt = $imageInfo->post_date_gmt;
        $imageInfo->post_name = $name;
        $imageInfo->post_parent = 0;
        $imageInfo->post_password = "";
        $imageInfo->post_status = "inherit";
        $imageInfo->post_title = $name;
        $imageInfo->post_type = "attachment";

        $year = date("Y");
        $month = date("n");
        if (!Utils::checkCreateFolder(PUBLIC_UPLOAD_PATH . $year)) {
            return NULL;
        }

        if (!Utils::checkCreateFolder(PUBLIC_UPLOAD_PATH . $year . DIRECTORY_SEPARATOR . $month)) {
            return NULL;
        }

        $folder = PUBLIC_UPLOAD_PATH . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;
        $path_parts = pathinfo($name);
        $date = new DateTime();
        $name = $date->getTimestamp() . mt_rand();
        $file_name = $name . '.' . $path_parts['extension'];

        if (!copy($tmp_name, $folder . $file_name)) {
            return NULL;
        }
        $imageInfo->guid = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $file_name;
        $attachment_metadata->file = $folder . $file_name;
        $imageInfo->attached_file = $folder . $file_name;
        $imageInfo->attachment_post = $imageInfo;
        if ($this->is_create_thumb) {
            $attachment_metadata->sizes = new Image_SizesBO();
            $path = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/";

            if ($this->is_slider_thumb) {
                if (!(isset($this->slider_thumb_width) && is_numeric($this->slider_thumb_width))) {
                    $this->slider_thumb_width = SIZE_WITH_SLIDER_THUMB;
                }
                if ($attachment_metadata->width >= $this->slider_thumb_width) {
                    if (!(isset($this->slider_thumb_height) && is_numeric($this->slider_thumb_height))) {
                        $this->slider_thumb_height = SIZE_WITH_SLIDER_THUMB * $imageSizes[1] / $imageSizes[0];
                        ;
                    }

                    $this->createThumbnail($attachment_metadata, $tmp_name, "slider_thumb", $imageSizes[0], $imageSizes[1], $this->slider_thumb_width, $this->slider_thumb_height, $folder, $path, $name, $path_parts['extension'], $this->slider_thumb_crop, $this->slider_thumb_quality);
                }
            }
            if ($this->is_thumbnail) {
                if (!(isset($this->thumbnail_width) && is_numeric($this->thumbnail_width))) {
                    $this->thumbnail_width = SIZE_WITH_THUMBNAIL;
                }
                if ($attachment_metadata->width >= $this->thumbnail_width) {
                    if (!(isset($this->thumbnail_height) && is_numeric($this->thumbnail_height))) {
                        $this->thumbnail_height = SIZE_WITH_THUMBNAIL * $imageSizes[1] / $imageSizes[0];
                        ;
                    }

                    $this->createThumbnail($attachment_metadata, $tmp_name, "thumbnail", $imageSizes[0], $imageSizes[1], $this->thumbnail_width, $this->thumbnail_height, $folder, $path, $name, $path_parts['extension'], $this->thumbnail_crop, $this->thumbnail_quality);
                }
            }
            if ($this->is_post_thumbnail) {
                if (!(isset($this->post_thumbnail_width) && is_numeric($this->post_thumbnail_width))) {
                    $this->post_thumbnail_width = SIZE_WITH_POST_THUMBNAIL;
                }
                if ($attachment_metadata->width >= $this->post_thumbnail_width) {
                    if (!(isset($this->post_thumbnail_height) && is_numeric($this->post_thumbnail_height))) {
                        $this->post_thumbnail_height = SIZE_WITH_POST_THUMBNAIL * $imageSizes[1] / $imageSizes[0];
                        ;
                    }

                    $this->createThumbnail($attachment_metadata, $tmp_name, "post_thumbnail", $imageSizes[0], $imageSizes[1], $this->post_thumbnail_width, $this->post_thumbnail_height, $folder, $path, $name, $path_parts['extension'], $this->post_thumbnail_crop, $this->post_thumbnail_quality);
                }
            }
            if ($this->is_medium) {
                if (!(isset($this->medium_width) && is_numeric($this->medium_width))) {
                    $this->medium_width = SIZE_WITH_MEDIUM;
                }
                if ($attachment_metadata->width >= $this->medium_width) {
                    if (!(isset($this->medium_height) && is_numeric($this->medium_height))) {
                        $this->medium_height = SIZE_WITH_MEDIUM * $imageSizes[1] / $imageSizes[0];
                        ;
                    }

                    $this->createThumbnail($attachment_metadata, $tmp_name, "medium", $imageSizes[0], $imageSizes[1], $this->medium_width, $this->medium_height, $folder, $path, $name, $path_parts['extension'], $this->medium_crop, $this->medium_quality);
                }
            }
            if ($this->is_medium_large) {
                if (!(isset($this->medium_large_width) && is_numeric($this->medium_large_width))) {
                    $this->medium_large_width = SIZE_WITH_MEDIUM_LARGE;
                }
                if ($attachment_metadata->width >= $this->medium_large_width) {
                    if (!(isset($this->medium_large_height) && is_numeric($this->medium_large_height))) {
                        $this->medium_large_height = SIZE_WITH_MEDIUM_LARGE * $imageSizes[1] / $imageSizes[0];
                        ;
                    }

                    $this->createThumbnail($attachment_metadata, $tmp_name, "medium_large", $imageSizes[0], $imageSizes[1], $this->medium_large_width, $this->medium_large_height, $folder, $path, $name, $path_parts['extension'], $this->medium_large_crop, $this->medium_large_quality);
                }
            }
            if ($this->is_large) {
                if (!(isset($this->large_width) && is_numeric($this->large_width))) {
                    $this->large_width = SIZE_WITH_LARGE;
                }
                if ($attachment_metadata->width >= $this->large_width) {
                    if (!(isset($this->large_height) && is_numeric($this->large_height))) {
                        $this->large_height = SIZE_WITH_LARGE * $imageSizes[1] / $imageSizes[0];
                        ;
                    }

                    $this->createThumbnail($attachment_metadata, $tmp_name, "large", $imageSizes[0], $imageSizes[1], $this->large_width, $this->large_height, $folder, $path, $name, $path_parts['extension'], $this->large_crop, $this->large_quality);
                }
            }
        }

        $imageInfo->attachment_metadata = $attachment_metadata;

        return $this->addToDatabase($imageInfo);
    }

    /**
     * 
     * @param type $name_image
     * @return boolean images_id
     */
    public function uploadImages($name_image)
    {
        if (isset($_FILES[$name_image])) {
            $images_id_array = array();
            //You need to handle both cases
            //If Any browser does not support serializing of multiple images using FormData() 
            if (!is_array($_FILES[$name_image]['name'])) { //single image
                $image_id = $this->uploadImage($_FILES[$name_image]['name'], $_FILES[$name_image]['tmp_name']);
                $images_id_array[] = $image_id;
            } else {  //Multiple images, image[]
                $image_count = count($_FILES[$name_image]['name']);
                for ($i = 0; $i < $image_count; $i++) {
                    $image_id = $this->uploadImage($_FILES[$name_image]['name'][$i], $_FILES[$name_image]['tmp_name'][$i]);
                    if ($image_id == FALSE) {
                        return FALSE;
                    } else {
                        $images_id_array[] = $image_id;
                    }
                }
            }
            return $images_id_array;
        }
    }

    /**
     * 
     * @param type $source_image
     * @param type $destination_filename
     * @param type $width
     * @param type $height
     * @param type $crop
     * @param type $quality
     * @return boolean
     */
    public function resize_image($source_image, $destination_filename, $width = 1024, $height = 1024, $crop = true, $quality = 100)
    {
        $image_data = getimagesize($source_image);
        if (!$image_data) {
            return false;
        }

        // set to-be-used function according to filetype
        switch ($image_data['mime']) {
            case 'image/gif':
                $get_func = 'imagecreatefromgif';
                $suffix = ".gif";
                break;
            case 'image/jpeg';
                $get_func = 'imagecreatefromjpeg';
                $suffix = ".jpg";
                break;
            case 'image/png':
                $get_func = 'imagecreatefrompng';
                $suffix = ".png";
                break;
        }

        $img_original = call_user_func($get_func, $source_image);
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round($old_width / $old_height, 2);
        $desired_ratio_after = round($width / $height, 2);
        $desired_ratio_before = round($height / $width, 2);

        if ($old_width < $width OR $old_height < $height) {
            // the desired image size is bigger than the original image. Best not to do anything at all really.
            // save original file to folder avatar        
            if (!copy($source_image, $destination_filename)) {
                return FALSE;
            } else {
                return TRUE;
            }
        }

        // if crop is on: it will take an image and best fit it so it will always come out the exact specified size.
        if ($crop) {
            // create empty image of the specified size
            $new_image = imagecreatetruecolor($width, $height);

            // landscape image
            if ($current_ratio > $desired_ratio_after) {
                $new_width = $old_width * $height / $old_height;
            }

            // nearly square ratio image
            if ($current_ratio > $desired_ratio_before AND $current_ratio < $desired_ratio_after) {

                if ($old_width > $old_height) {
                    $new_height = max($width, $height);
                    $new_width = $old_width * $new_height / $old_height;
                } else {
                    $new_height = $old_height * $width / $old_width;
                }
            }

            // portrait sized image
            if ($current_ratio < $desired_ratio_before) {
                $new_height = $old_height * $width / $old_width;
            }

            // find ratio of original image to find where to crop
            $width_ratio = $old_width / $new_width;
            $height_ratio = $old_height / $new_height;

            // calculate where to crop based on the center of the image
            $src_x = floor((($new_width - $width) / 2) * $width_ratio);
            $src_y = round((($new_height - $height) / 2) * $height_ratio);
        }
        // don't crop the image, just resize it proportionally
        else {
            if ($old_width > $old_height) {
                $ratio = max($old_width, $old_height) / max($width, $height);
            } else {
                $ratio = max($old_width, $old_height) / min($width, $height);
            }

            $new_width = $old_width / $ratio;
            $new_height = $old_height / $ratio;
            $new_image = imagecreatetruecolor($new_width, $new_height);
        }

        // create avatar thumbnail
        imagecopyresampled($new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height);

        // save it as a .jpg file with our $destination_filename parameter
        imagejpeg($new_image, $destination_filename, $quality);

        // delete "working copy" and original file, keep the thumbnail
        imagedestroy($new_image);
        imagedestroy($img_original);

        if (file_exists($destination_filename)) {
            return true;
        }
        // default return
        return false;
    }

    public function delete($post_id)
    {
        try {
            $postBO = $this->get($post_id);
            if (isset($postBO->attachment_metadata) && isset($postBO->attachment_metadata->sizes)) {
                if (isset($postBO->attachment_metadata->sizes->slider_thumb) && isset($postBO->attachment_metadata->sizes->slider_thumb->file)) {
                    Utils::deleteFile($postBO->attachment_metadata->sizes->slider_thumb->file);
                }
                if (isset($postBO->attachment_metadata->sizes->thumbnail) && isset($postBO->attachment_metadata->sizes->thumbnail->file)) {
                    Utils::deleteFile($postBO->attachment_metadata->sizes->thumbnail->file);
                }
                if (isset($postBO->attachment_metadata->sizes->post_thumbnail) && isset($postBO->attachment_metadata->sizes->post_thumbnail->file)) {
                    Utils::deleteFile($postBO->attachment_metadata->sizes->post_thumbnail->file);
                }
                if (isset($postBO->attachment_metadata->sizes->medium) && isset($postBO->attachment_metadata->sizes->medium->file)) {
                    Utils::deleteFile($postBO->attachment_metadata->sizes->medium->file);
                }
                if (isset($postBO->attachment_metadata->sizes->medium_large) && isset($postBO->attachment_metadata->sizes->medium_large->file)) {
                    Utils::deleteFile($postBO->attachment_metadata->sizes->medium_large->file);
                }
                if (isset($postBO->attachment_metadata->sizes->large) && isset($postBO->attachment_metadata->sizes->large->file)) {
                    Utils::deleteFile($postBO->attachment_metadata->sizes->large->file);
                }
            }
            if (parent::delete($post_id)) {
                return TRUE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }
}

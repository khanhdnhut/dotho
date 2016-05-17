<?php
if (isset($this->productBO) && $this->productBO != NULL) {

    ?>
    <link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />

    <div class="breadcrumbs">
        <img alt="icon home" src="<?php echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_57_DEFAULT; ?>" style="width: 22px;">
        <a href="<?php echo URL; ?>"> Trang chủ</a> &gt;&gt; 
        <?php
        if (isset($this->productBO->categoryArray) && is_array($this->productBO->categoryArray) && count($this->productBO->categoryArray) > 0) {
            $categoryArray = $this->productBO->categoryArray;
            for ($i = count($categoryArray) - 1; $i >= 0; $i--) {
                $categoryBO = $categoryArray[$i];
                if ($categoryBO != null && isset($categoryBO->term_taxonomy_id) &&
                    isset($categoryBO->name)) {

                    ?>
                    <a href="<?php echo URL . CATEGORY_CP_VIEW . $categoryBO->term_taxonomy_id . "/1/" . $categoryBO->slug; ?>"><?php echo $categoryBO->name; ?></a> &gt;&gt; 
                    <?php
                }
            }
        }

        ?>
        <span class="lastitem"><?php
            if (isset($this->productBO->post_title)) {
                echo $this->productBO->post_title;
            }

            ?></span>
    </div>

    <div itemtype="http://schema.org/Product" itemscope="" class="col-sm-12">
        <div class="col-sm-12">
            <?php
            if (isset($this->productBO->images)) {
                $this->slideshow = new stdClass();
                $this->slideshow->images = $this->productBO->images;
//                require VIEW_TEMPLATES_PATH . 'home/slideshow.php';
//                require VIEW_TEMPLATES_PATH . 'home/slideshow2.php';
//                require VIEW_TEMPLATES_PATH . 'home/slideshow2j.php';
                require VIEW_TEMPLATES_PATH . 'home/slideshow3.php';
//                require VIEW_TEMPLATES_PATH . 'home/slideshow4.php';
//                require VIEW_TEMPLATES_PATH . 'home/slideshow5.php';
            }

            ?>
            <div class="clearfix"></div>
            <br>
        </div>

        <div class="col-sm-12">
            <h1 style="margin-top: 0px; text-transform: uppercase; font-weight: bold; margin-bottom: 20px; font-size: 15px;"><span itemprop="name"><?php
                    if (isset($this->productBO->post_title)) {
                        echo $this->productBO->post_title;
                    }

                    ?></span></h1>
            <div>

                <?php
                if (isset($this->productBO->post_content)) {
                    echo $this->productBO->post_content;
                }

                ?>
            </div>
            <div class="inner_support">
                <p><?php echo DEFAULT_SITE_NAME; ?></p>
                <p><?php echo TITLE_ADDRESS; ?>: <?php echo DEFAULT_ADDRESS; ?></p>
                <?php echo TITLE_HOTLINE; ?>: 
                <a href="tel:<?php echo DEFAULT_PHONE_VALUE; ?>">
                    <strong><?php echo DEFAULT_PHONE; ?></strong>
                </a>
                <a href="tel:<?php echo DEFAULT_PHONE_VALUE_2; ?>">
                    <strong> / <?php echo DEFAULT_PHONE_2; ?>	</strong>
                </a>
            </div>
        </div>

        <div class="col-sm-12">
            <p style="border-bottom:1px solid #eee; margin-top:10px;">
                <b>
                    <?php echo TITLE_DETAIL_IMAGE; ?>
                </b>
            </p>
            <div class="chitietspimg">
                <?php
                if (isset($this->productBO->images)) {
                    $stt = 1;
                    foreach ($this->productBO->images as $image) {
                        if (isset($image->image_url)) {

                            ?>
                            <p style="text-align: center;"><img style="max-width: 100%;" 
                                    alt="<?php
                                    if (isset($this->productBO->post_title)) {
                                        echo $this->productBO->post_title;
                                    }

                                    ?>" 
                                    src="<?php echo URL . $image->large_url; ?>" 
                                    title="<?php
                                    if (isset($this->productBO->post_title)) {
                                        echo $this->productBO->post_title;
                                    }

                                    ?>" 
                                    class="stylemoi"></p>
                            <p style="text-align: center; margin-bottom: 20px;">
                                <em><?php echo TITLE_INDEX_IMAGE; ?> <?php echo $stt++; ?></em>
                            </p>
                            <?php
                        }
                    }
                }

                ?> 
            </div>
        </div>        
    </div>
    <?php
}

?>

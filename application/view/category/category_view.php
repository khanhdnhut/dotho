
<div class="breadcrumbs">
    <img alt="icon home" src="<?php echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_57_DEFAULT; ?>" style="width: 22px;">
    <a href="<?php echo URL; ?>"> Trang chủ</a> 
    <?php
    if (isset($this->categoryArray) && is_array($this->categoryArray) && count($this->categoryArray) > 0) {
        $categoryArray = $this->categoryArray;
        for ($i = count($categoryArray) - 1; $i >= 0; $i--) {
            $categoryBO = $categoryArray[$i];
            if ($categoryBO != null && isset($categoryBO->term_taxonomy_id) &&
                isset($categoryBO->name)) {

                ?>
                &gt;&gt; <a href="<?php echo URL . CATEGORY_CP_VIEW . $categoryBO->term_taxonomy_id . "/1/" . $categoryBO->slug; ?>"><?php echo $categoryBO->name; ?></a>
                <?php
            }
        }
    }

    ?>
    <span class="lastitem"><?php
        if (isset($this->post_title)) {
            echo $this->post_title;
        }

        ?></span>
</div>
<?php
if (isset($this->productBOArray) && is_array($this->productBOArray) &&
    count($this->productBOArray) > 0) {
    $index = 0;
    foreach ($this->productBOArray as $productBO) {
        $index++;

        ?>
        <div class="col-sm-3 col-xs-6" style="padding-right: 0px; margin-bottom: 5px;">
            <div class="product-image-wrapper">
                <div itemtype="http://schema.org/Product" itemscope="" class="productinfo text-center">
                    <a  style="text-decoration: none;" href="<?php
                    if (isset($productBO->ID) && isset($productBO->post_name)) {
                        echo URL . PRODUCT_CP_VIEW . $productBO->ID . "/" . $productBO->post_name;
                    }

                    ?>" rel="nofollow">
                        <img  alt="<?php
                        if (isset($productBO->post_title)) {
                            echo $productBO->post_title;
                        }

                        ?>" title="<?php
                              if (isset($productBO->post_title)) {
                                  echo $productBO->post_title;
                              }

                              ?>" src="<?php
                              if (isset($productBO->imagePresentation) && isset($productBO->imagePresentation->large_url)) {
                                  echo URL . $productBO->imagePresentation->large_url;
                              }

                              ?>" itemprop="image">
                    </a>	
                    <p>
                        <a  style="text-decoration: none;" href="<?php
                        if (isset($productBO->ID) && isset($productBO->post_name)) {
                            echo URL . PRODUCT_CP_VIEW . $productBO->ID . "/" . $productBO->post_name;
                        }

                        ?>">
                            <div  style="height: 40px;
                                  overflow: hidden;
                                  margin: 0px 5px;
                                  " itemprop="name"><?php
                                      if (isset($productBO->post_title)) {
                                          echo $productBO->post_title;
                                      }

                                      ?></div>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <?php
        if ($index % 4 == 0) {

            ?>
            <div class = "clearfix"></div>
            <?php
        } elseif ($index % 2 == 0) {

            ?>
            <div class = "clearfix visible-xs"></div>
            <?php
        }
    }
}

?>


<div class = "clearfix"></div>

<?php
if (isset($this->number_page) && is_numeric($this->number_page) && $this->number_page > 1 &&
    isset($this->page) && is_numeric($this->page) && $this->page > 0) {

    ?>
    <?php
    if (isset($this->categoryArray) && is_array($this->categoryArray) && count($this->categoryArray) > 0) {

        ?>
        <div id="pagination" style="text-align: center;">
            <ul class="pagination">
                <?php
                for ($i = 0; $i < $this->number_page; $i++) {
                    if ($i + 1 == $this->page) {

                        ?>
                        <li class="active"><a href="<?php echo URL . CATEGORY_CP_VIEW . $this->categoryArray[0]->term_taxonomy_id . "/" . ($i + 1) . "/" . $this->categoryArray[0]->slug; ?>"><?php echo $i + 1; ?></a></li>
                            <?php
                        } else {

                            ?>
                        <li ><a href="<?php echo URL . CATEGORY_CP_VIEW . $this->categoryArray[0]->term_taxonomy_id . "/" . ($i + 1) . "/" . $this->categoryArray[0]->slug; ?>"><?php echo $i + 1; ?></a></li>
                        <?php
                    }
                }

                ?>
            </ul>
        </div>  
        <?php
    }

    ?>


    <?php
}

?>


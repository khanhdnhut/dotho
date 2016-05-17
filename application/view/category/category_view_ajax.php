<div class="features_items" style="padding-top: 10px; padding-bottom: 6px; margin-right: 5px; margin-top: 5px; padding-right: 10px;"> 
    <div class="breadcrumbs" style="padding: 0px; margin-top: -5px; ">
        <?php
        if (isset($this->categoryArray) && is_array($this->categoryArray) && count($this->categoryArray) > 0) {
            $categoryArray = $this->categoryArray;

            ?>
            <h3 class="title"><a href="<?php echo URL . CATEGORY_CP_VIEW . $this->categoryArray[0]->term_taxonomy_id . "/1/" . $this->categoryArray[0]->slug; ?>" style="text-decoration: none; color: orange; font-size: 18px;"><?php echo strtoupper($this->categoryArray[0]->name); ?></a></h3>
                                 <?php
                             }

                             ?>
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

</div>
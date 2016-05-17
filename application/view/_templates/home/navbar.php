<div class="menu" style="position:relative;">
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu" style="width:122px;">
                <div style="float:left;margin-right:2px;">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </div>
                <p style="margin:-3px 0 0 0px;">DANH MỤC</p>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="main-menu">
            <ul class="nav navbar-nav">
                <li style="position:static;" class="dropdown divide-li trangchu-icon">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="<?php echo URL; ?>">Sản Phẩm</a>
                    <ul style="left:0!important;right:0!important;" class="dropdown-menu">
                        <div style="padding-left:15px;padding-right:15px;" class="row">
                            <?php
                            Controller::autoloadController("category");
                            $categoryCtrl = new CategoryCtrl();
                            $categoryCtrl->getNavBar();

                            ?>
                        </div>
                    </ul>
                </li>


                <li style="position:static;" class="dropdown divide-li">
                    <a href="<?php echo URL; ?>gioi-thieu">Giới Thiệu</a>
                </li>

                <li style="position:static;" class="dropdown divide-li">
                    <a href="<?php echo URL; ?>lien-he">Liên Hệ</a>
                </li>
            </ul>
        </div> 
    </nav>	

    <div class="search_box pull-right" style="margin-top:3px;position:absolute;right:5px;top:0px;">

    </div>
</div>
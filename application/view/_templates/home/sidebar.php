<div class="col-sm-2half hidden-xs" style="padding-left: 5px; padding-right: 5px;">
    <?php
    Controller::autoloadController("category");
    $categoryCtrl = new CategoryCtrl();
    $categoryCtrl->getSideBar();
    ?>
</div>


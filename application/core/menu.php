<?php

class MenuItem {

    public $menu_is_ul = false;
    public $menu_active = false;
    public $menu_active_class = "";
    public $menu_first = false;
    public $menu_last = false;
    public $menu_index_class = "";
    public $menu_level = 0;
    public $menu_id_begin = 0;
    public $menu_z_index_begin = 1000;
    public $menu_title = null;
    public $menu_href = null;
    public $menu_children = array();
    public $menu_columns = array();
    public $menu_render = "";
    
    
    public function getRenderMenu(){
        if ($this->menu_is_ul) {            
                $this->menu_render = "<ul class='megamenu level".$this->menu_level."'>";
                if (count($this->menu_children) > 0) {
                    for ($i = 0; $i <= count($this->menu_children); $i++) {
                        if ($this->menu_children[$i] instanceof MenuItem) {
                            $this->menu_children[$i]->menu_id_begin = $this->menu_id_begin++;
                            $this->menu_children[$i]->menu_z_index_begin = $this->menu_z_index_begin + 1;
                            $this->menu_children[$i]->menu_level = $this->menu_level + 1;   
                           
                            $this->menu_render .= $this->menu_children[$i]->getRenderMenu();  
                            
                            $this->menu_id_begin = $this->menu_children[$i]->menu_id_begin++;
                            $this->menu_z_index_begin = $this->menu_children[$i]->menu_z_index_begin + 1;  
                        }
                    }
                }                
                $this->menu_render .= "</ul>";     
        } else {
            if(!is_null($this->menu_title)){
                if (count($this->menu_children) > 0 || count($this->menu_columns) > 0) {
                    //Chứng tỏ đây không phải lá
                    if (count($this->menu_children) > 0) {
                        $this->menu_render .= "
                            <li class='mega haschild'>
                                <a title='".$this->menu_title."' id='menu522' class='mega haschild' href='".$this->menu_href."'>
                                    <span class='menu-title'>".$this->menu_title."</span>
                                </a>
                                <div class='childcontent cols1 ' style='width: 222px; height: 200px; display: none; margin-left: 0px; left: 344px; z-index: ".$this->menu_z_index_begin++.";'>
                                    <div class='childcontent-inner-wrap' style='width: 202px;'>
                                        <div style='width: 200px;' class='childcontent-inner clearfix'>
                                            <div style='width: 200px;' class='megacol column1 first'>
						<ul class='megamenu level".$this->menu_level."'>";
                                                
                        for($i = 0; $i <= count($this->menu_children); $i++) {
                            if ($this->menu_children[$i] instanceof MenuItem) {
                                if ($i = 0 && $i < count($this->menu_children)) {
                                    $this->menu_children[$i]->menu_first = true;
                                    $this->menu_children[$i]->menu_last = false;
                                } elseif ($i == count($this->menu_children) - 1){
                                    $this->menu_children[$i]->menu_first = false;
                                    $this->menu_children[$i]->menu_last = true;
                                }
                                $this->menu_children[$i]->menu_id_begin = $this->menu_id_begin++;
                                $this->menu_children[$i]->menu_z_index_begin = $this->menu_z_index_begin + 1;
                                $this->menu_children[$i]->menu_level = $this->menu_level + 1;                            
                                $this->menu_render .= $this->menu_children[$i]->getRenderMenu();

                                $this->menu_id_begin = $this->menu_children[$i]->menu_id_begin++;
                                $this->menu_z_index_begin = $this->menu_children[$i]->menu_z_index_begin + 1;  
                            }                        
                        }
                        
                        $this->menu_render .= "</ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>";
                    } elseif (count($this->menu_columns) > 0) {
                        for($i = 0; $i <= count($this->menu_columns); $i++) {
                            if ($this->menu_columns[$i] instanceof MenuItem) {
                                $this->menu_render .= "
                                    <li class='mega haschild'>
                                        <a title='".$this->menu_title."' id='menu".$this->menu_id_begin++."' class='mega haschild ' href='".$this->menu_href."'>
                                            <span class='menu-title'>".$this->menu_title."</span>
                                        </a>
                                        <div class='childcontent cols".count($this->menu_columns)." ' style='display: none; margin-left: 0px; left: 0px; z-index: ".$this->menu_z_index_begin++.";'>
                                            <div class='childcontent-inner-wrap' style='width: 982px;'>
                                                <div style='width: 980px;' class='childcontent-inner clearfix'>";
                                
                                for($i = 0; $i <= count($this->menu_columns); $i++) {
                                    if ($this->menu_columns[$i] instanceof MenuItem) {
                                        if ($i = 0 && $i < count($this->menu_columns)) {
                                            $this->menu_index_class = "first";
                                        } elseif ($i == count($this->menu_columns) - 1){
                                            $this->menu_index_class = "last";
                                        } else {
                                            $this->menu_index_class = "";
                                        }
                                        
                                        $this->menu_columns[$i]->menu_id_begin = $this->menu_id_begin++;
                                        $this->menu_columns[$i]->menu_z_index_begin = $this->menu_z_index_begin + 1;
                                        $this->menu_columns[$i]->menu_level = $this->menu_level + 1;
                                        
                                        $this->menu_render .= "<div style='width: 240px;' class='megacol column".($i + 1)." ".$this->menu_index_class."'>"; 
                                        $this->menu_render .= $this->menu_columns[$i]->getRenderMenu();
                                        $this->menu_render .= "</div>";

                                        $this->menu_id_begin = $this->menu_columns[$i]->menu_id_begin++;
                                        $this->menu_z_index_begin = $this->menu_columns[$i]->menu_z_index_begin + 1;  
                                    }                        
                                }

                                $this->menu_render .= "
                                                </div>
                                            </div>
                                        </div>
                                    </li>";
                            }                        
                        }
                    }                

                } else {
                    //Chứng tỏ đây là lá
                    if ($this->menu_first) {
                        $this->menu_index_class = "first";
                    } elseif ($this->menu_last) {
                        $this->menu_index_class = "last";                    
                    }

                    if ($this->menu_active) {
                        $this->menu_active_class = "active";
                    }

                    $this->menu_render .= "<li class='mega ".$this->menu_index_class." ".$this->menu_active_class."'>";
                    $this->menu_render .= "<a title='".$this->menu_title."' id='menu".$this->menu_id_begin++."' class='mega ".$this->menu_index_class." ".$this->menu_active_class."' href='".$this->menu_href."'>";
                    $this->menu_render .= "<span class='menu-title'>".$this->menu_title."</span>";
                    $this->menu_render .= "</a>";
                    $this->menu_render .= "</li>"; 
                }
            } 
        }
        
        
        
        return $this->menu_render;
    }


    public function pushChildren($menu_item) {
        array_push($this->menu_children, $menu_item);
    }
    
    public function pushColumn($menu_item) {
        array_push($this->menu_columns, $menu_item);
    }
    
    function getMenu_first() {
        return $this->menu_first;
    }

    function getMenu_last() {
        return $this->menu_last;
    }

    function getMenu_index_class() {
        return $this->menu_index_class;
    }

    function setMenu_first($menu_first) {
        $this->menu_first =
                $menu_first;
    }

    function setMenu_last($menu_last) {
        $this->menu_last =
                $menu_last;
    }

    function setMenu_index_class($menu_index_class) {
        $this->menu_index_class =
                $menu_index_class;
    }
        
    function getMenu_id_begin() {
        return $this->menu_id_begin;
    }

    function setMenu_id_begin($menu_id_begin) {
        $this->menu_id_begin =
                $menu_id_begin;
    }
        
    function getMenu_level() {
        return $this->menu_level;
    }

    function getMenu_render() {
        return $this->menu_render;
    }

    function setMenu_level($menu_level) {
        $this->menu_level =
                $menu_level;
    }

    function setMenu_render($menu_render) {
        $this->menu_render =
                $menu_render;
    }
    
    function getMenu_active() {
        return $this->menu_active;
    }

    function setMenu_active($menu_active) {
        $this->menu_active =
                $menu_active;
    }
        
    public function getMenu_title() {
        return $this->menu_title;
    }

    public function getMenu_href() {
        return $this->menu_href;
    }

    public function getMenu_children() {
        return $this->menu_children;
    }

    public function getMenu_columns() {
        return $this->menu_columns;
    }

    public function setMenu_title($menu_title) {
        $this->menu_title =
                $menu_title;
    }

    public function setMenu_href($menu_href) {
        $this->menu_href =
                $menu_href;
    }

    public function setMenu_children($menu_children) {
        $this->menu_children =
                $menu_children;
    }

    public function setMenu_columns($menu_columns) {
        $this->menu_columns =
                $menu_columns;
    }

}

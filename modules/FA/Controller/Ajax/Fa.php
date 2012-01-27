<?php
class Controller_Ajax_Fa extends Modules_Ajax {
    private $count = 0;
    
    private $file = array();
    
    private $tree = null;
    
    function save() {
    	$save = new Controller_Ajax_FASave($this->config);
    	$save->index();
    }
}
?>
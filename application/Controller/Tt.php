<?php
class Controller_Tt extends Controller_Index {
    protected $registry;
    
	public function __construct($registry, $action, $args) {
		$this->registry = $registry;
	}
	
	public function index($args) {       
        if (isset($args[0])) {
            if ($args[0] == "add") {
                
                $controller = new Controller_Tt_Add($this->registry);
                $controller->index($args);
                
            } elseif ($args[0] == "list") {
            
                $controller = new Controller_Tt_Index($this->registry);
                $controller->index($args);
             
            } elseif ($args[0] == "cal") {
                
                $controller = new Controller_Tt_Cal($this->registry);
                $controller->index($args);
                
            } elseif ($args[0] == "new") {
                
                $controller = new Controller_Tt_New($this->registry);
                $controller->index($args);

            }  elseif ($args[0] == "page") {
                
                $controller = new Controller_Tt_Index($this->registry);
                $controller->index($args);
                
            }  elseif ($args[0] == "edit") {
                
                $controller = new Controller_Tt_Edit($this->registry);
                $controller->index($args);
                        
            } else {
                
                $controller = new Controller_Tt_Show($this->registry);
                $controller->index($args);
                
            }
        } else {
            $controller = new Controller_Tt_Index($this->registry);
            $controller->index($args);
        }
    }
}
?>
<?php
class Controller_Objects extends Controller_Index {
    protected $registry;
    
	public function __construct($registry, $action, $args) {
		$this->registry = $registry;
	}
	
	public function index($args) {
        if (isset($args[0])) {
            if ($args[0] == "add") {
                
                $controller = new Controller_Objects_Add($this->registry);
                $controller->index($args);

            } elseif ($args[0] == "list") {
                
                $controller = new Controller_Objects_List($this->registry);
                $controller->index($args);
                                    
            } elseif ($args[0] == "edit") {
                
                $controller = new Controller_Objects_Edit($this->registry);
                $controller->index($args);
            
            } elseif  ($args[0] == "page") {
                
                $controller = new Controller_Objects_Index($this->registry);
                $controller->index($args);
                        
            } else {
                
                $controller = new Controller_Objects_Show($this->registry);
                $controller->index($args);
                
            }
        } else {
            $controller = new Controller_Objects_Index($this->registry);
            $controller->index($args);
        }
    }
}
?>
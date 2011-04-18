<?php
class Controller_Find extends Controller_Index {
    protected $registry;
    
	public function __construct($registry, $action, $args) {
		$this->registry = $registry;
	}
	
	public function index($args) {
        if (isset($args[0])) {
            if ($args[0] == "objects") {
                
                $controller = new Controller_Find_Objects($this->registry);
                $controller->index($args);

            } elseif ($args[0] == "tasks") {
                
                $controller = new Controller_Find_Tasks($this->registry);
                $controller->index($args);
                                    
            }
        } else {
            $controller = new Controller_Find_Objects($this->registry);
            $controller->index($args);
        }
    }
}
?>
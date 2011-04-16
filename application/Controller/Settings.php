<?php
class Controller_Settings extends Controller_Index {
    protected $registry;
    
	public function __construct($registry, $action, $args) {
		$this->registry = $registry;
	}
	
	public function index($args) {        
        if (isset($args[0])) {
            if ($args[0] == "users") {
                
                $controller = new Controller_Settings_Users($this->registry);
                $controller->index($args);

            } elseif ($args[0] == "tt") {
                
                $controller = new Controller_Settings_Tt($this->registry);
                $controller->index($args);
                    
            } elseif ($args[0] == "kb") {
                
                $controller = new Controller_Settings_Kb($this->registry);
                $controller->index($args);

            } elseif ($args[0] == "templates") {
                
                $controller = new Controller_Settings_Templates($this->registry);
                $controller->index($args);
                
            }
        } else {
            $controller = new Controller_Settings_Index($this->registry);
            $controller->index($args);
        }
    }
}
?>
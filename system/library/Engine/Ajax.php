<?php
class Engine_Ajax extends Engine_Interface {
	
	protected $view;
    protected $memcached;
    
    protected $module_name;
    protected $module_path;
    protected $config;

	function __construct() {
		parent::__construct();
	}
    
    public function __call($name, $args) {
        $action = $args[0]["action"];
        $this->errorload($action);
    }
    
    private function errorload($name) {
        echo "<p>Error load Ajax controller: " . $name . "</p>";
    }
}
?>
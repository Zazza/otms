<?php
class Main {
    private $_config = null;
    
    private $main;
    
	function __construct($config) {
        $this->_config = $config;
        
        require_once 'Twig/Autoloader.php';
        
        $content = new Twig_Loader_Filesystem($config['path']['layouts']);
        $layouts = new Twig_Environment($content, array(
        	'cache' => $config['path']["cache"],
            //'cache' => FALSE,
            'autoescape' => FALSE
        ));
        
        $this->main = $layouts;
	}
    
	public function getTemplate($template) {
		$dirClass = explode("_", $template);
	
		if (sizeof($dirClass) > 1) {
			$template = implode(DIRECTORY_SEPARATOR, $dirClass) . '.tpl';
		} else
		{
			$template = $template . '.tpl';
		};
	
		return $template;
	}
    
    function index() {
		$template = $this->main->loadTemplate("content.tpl");
		$template->display(array("url" => $this->_config["url"], "upload" => $this->_config["upload"]));
    }
}
?>
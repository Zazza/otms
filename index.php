<?php
error_reporting(E_ALL);

if (file_exists("requirements.php")) {
    include("requirements.php");
} else {
    require_once 'system/settings/config.php';
    
    $config["path"]["root"] = dirname(__FILE__);
    $config["url"] = $_SERVER["HTTP_HOST"];
    $config["ip"] = $_SERVER['REMOTE_ADDR'];
    $config["uri"] = $_SERVER["REQUEST_URI"];
    
    $paths = implode(PATH_SEPARATOR,
        array(
            $config["path"]["root"] . $config['path']['library'],
            $config["path"]["root"] . $config['path']['system'],
    		$config["path"]["root"] . $config['path']['application']
        ));
    
    set_include_path($paths);

    function __autoload($class_name) {
    	$dirClass = explode("_", $class_name);
    
    	if (sizeof($dirClass) > 1) {
    		$class_name = implode(DIRECTORY_SEPARATOR, $dirClass) . '.php';
    	} else
    	{
    		$class_name = $class_name . '.php';
    	};
    
    	@require_once $class_name;
    }
    
    $bootstrap = new Bootstrap();
    $bootstrap->run($config);
}
?>
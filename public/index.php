<?php
// Generation page time
$start_time = microtime();

error_reporting(E_ALL);

if (version_compare(phpversion(), '5.3.0', '<') == true) { die ('PHP5.3 Only'); }

require_once '../application/settings/config.php';

$config["path"]["root"] = dirname(__FILE__) . '/../';
$config["url"] = $_SERVER["HTTP_HOST"];
$config["ip"] = $_SERVER['REMOTE_ADDR'];

$paths = implode(PATH_SEPARATOR,
    array(
        $config["path"]["root"] . $config['path']['library'],
        $config["path"]["root"] . $config['path']['system'],
		$config["path"]["root"] . $config['path']['application']
    ));

set_include_path($paths);

// Singleton
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

$g_time = floatval( microtime() - $start_time );
//echo $g_time;
?>
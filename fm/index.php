<?php
require_once 'settings/config.php';

$paths = implode(PATH_SEPARATOR,
    array(
        $config['path']['library']
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

$action = (empty($_GET['main'])) ? '' : $_GET['main'];
if (empty($action)) { $action = 'index'; };

$action = trim($action, '/\\');
$action = quotemeta($action);

if ( ($action == "ajax") ) {
	$controller = new Ajax($config);
	
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $method = $_POST["action"];
            $controller->$method($_POST);
        }
	}
} else {
	if ($action == "index") {
		$controller = new Main($config);
		$controller->index();
	} else {
		$class = ucfirst($action);
		$controller = new $class($config);
		$controller->index();
	}
}
?>
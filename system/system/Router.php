<?php
class Router {
	private $registry;
	private $args;

	function __construct($registry) {
		$this->registry = $registry;
	}

	private function getArgs($arguments) {
		foreach($arguments as $part) {
			$this->args[] = quotemeta($part);
		}
	}
	
	function showContent() {
        
		$action = (empty($_GET['main'])) ? '' : $_GET['main'];
        if (empty($action)) { $action = 'index'; };
		
		// Получаем список аргументов без контроллера
		$action = trim($action, '/\\');
		$parts = explode('/', $action);

		$action = array_shift($parts);
		$action = quotemeta($action);
		
		// tags обрабатывается до контроллера!
		if ( ($action == "tag") and (isset($parts[0])) ) {
			$parts[0] = base64_encode($parts[0]);
		};
		
		// AjaxRequest обрабатывается до главного контроллера!
		if ( ($action == "ajax") and (isset($parts[0])) ) {
			$arguments = $parts;
		
			//Очистим URL
			$this->getArgs($arguments);
			
			// Находим файл
			if (is_file($this->registry["controller"] . ucfirst($action) . "/" . ucfirst($this->args[0]) . '.php')) {
				$class = 'Controller_Ajax_' . ucfirst($this->args[0]);
				$controller = new $class($this->registry, $this->args[0], $_POST);
				
				if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
				    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                        $method = $_POST["action"];
                        $controller->$method($_POST);
                    } else {
        				$controller = new Controller_Ajax_Index($this->registry, $this->args[0], $_POST);
        				$controller->errorload($this->args[0], $_POST);
                    }
				} else {
    				$controller = new Controller_Ajax_Index($this->registry, $this->args[0], $_POST);
    				$controller->errorload($this->args[0], $_POST);
				}
			} else {
				$controller = new Controller_Ajax_Index($this->registry, $this->args[0], $_POST);
				$controller->errorload($this->args[0], $_POST);
			}
		} else {
			
			$arguments = $parts;
		
			//Очистим URL
			$this->getArgs($arguments);

			// Находим файл
			if (!is_file($this->registry["controller"] . ucfirst($action) . '.php')) { 
				
				$class = 'Controller_Index';
				$controller = new $class($this->registry, $action, $this->args);
				
				$controller->__call($action, $this->args);
			} else {
				$class = 'Controller_' . ucfirst($action);
				$controller = new $class($this->registry, $action, $this->args);
				
				$controller->index($this->args);
			}
		}
	}
}

?>
<?php

 class Bootstrap {
    private $_config = null;
    private $registry = null;
    
    private $DBH = null;

    public function run($config) {
        try {
            $this->setConfig($config);
    
            $this->setView();
    
            $this->setDbAdapter();
    
            $this->setInit();
    
            $router = $this->setRouter();
        } catch (Exception $e) {
            // Перехват исключений 
            echo $e->getMessage();
        }
    }

     public function setConfig($config) {
        $this->_config = $config;
        
        // Активируем реестр
        $this->registry = new Registry;

        $this->registry->set('shortSiteName', $this->_config["url"]);
        $this->registry->set('siteName', "http://" . $this->registry["shortSiteName"]);

        $this->registry->set('keywords', $this->_config['keywords']['keywords']);
        $this->registry->set('description', $this->_config['keywords']['description']);
                
        $this->registry->set('controller', $this->_config["path"]["root"] . $this->_config['path']['controller']);
        $this->registry->set('cache', $this->_config["path"]["root"] . $this->_config['path']['cache']);
        $this->registry->set('rootPublic', $this->_config["path"]["root"] . $this->_config['path']['public']);
        $this->registry->set('rootDir', substr($this->_config["path"]["root"], 0, strpos($this->_config["path"]["root"], "public")));
        $this->registry->set('calYear', $this->_config["year"]);
        $this->registry->set('mailSender', $this->_config["mailSender"]);

        $this->registry->set('ip', $this->_config['ip']);
        
        $this->registry->set('local', $this->_config['local']);

		$action = (empty($_GET['main'])) ? '' : $_GET['main'];
        if (empty($action)) { $action = ''; };

        $this->registry->set('url', "/" . $action);
        
        if (empty($action)) {
            $uri = $this->_config["uri"];
        } else {
            $uri = substr($this->_config["uri"], 0, strpos($this->_config["uri"], $action));
        }
        $this->registry->set('uri', $uri);
     } 

     public function setView() {
		require_once 'Twig/Autoloader.php';
		
		$content = new Twig_Loader_Filesystem($this->_config["path"]["root"] . $this->_config['path']['layouts']);
		$layouts = new Twig_Environment($content, array(
			'cache' => $this->registry["cache"],
            //'cache' => FALSE,
            'autoescape' => FALSE
		));

		$loader = new Twig_Loader_Filesystem($this->_config["path"]["root"] . $this->_config['path']['templates']);
		$templates = new Twig_Environment($loader, array(
			'cache' => $this->registry["cache"],
            //'cache' => FALSE,
            'autoescape' => FALSE
		));
        
        $this->registry->set('layouts', $layouts);
        $this->registry->set('templates', $templates);
     }

     public function setDbAdapter() {
        try {  
        	$this->DBH = new PDO($this->_config['db']['adapter'] . ':host=' . $this->_config['db']['host'] . ';dbname=' . $this->_config['db']['dbname'], $this->_config['db']['username'], $this->_config['db']['password']);  
        } catch(PDOException $e) {  
        	echo $e->getMessage();  
        }
        
        $this->registry->set('db', $this->DBH);
        
        $this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        $this->DBH->query('SET NAMES UTF8');
     }
     
     public function setInit() {
        mb_internal_encoding("UTF-8");
                
		$view = new View_Index($this->registry);
        $this->registry->set('view', $view);
        
        $model = new Model_Index($this->registry);
        $this->registry->set('model', $model);
        
		$user = new Model_User($this->registry);
        $this->registry->set('user', $user);
        
		$tt = new Model_Tt($this->registry);
        $this->registry->set('tt', $tt);

		// checkCookie
		if (isset($_COOKIE["checkCookie"])) {
			$checkCookie = $_COOKIE["checkCookie"];
			if ($checkCookie == 1) {
                session_start();
                
				$this->registry->set("checkCookie", TRUE);
			}
		} else {
            $cookie_expire = time() + 86400;
			setcookie("checkCookie", "1", $cookie_expire, "/");
			$this->registry->set("checkCookie", FALSE);
		}

		$loginSession = & $_SESSION["login"];
        if (isset($loginSession["id"])) {
            $user->getInfo($loginSession);
        }
        
        $this->registry->set("ttgroups", $tt->getGroups());

		// Отобразим Keywords и Description по умолчанию
		$view->setDescription($this->registry["keywords"]);
		$view->setKeywords($this->registry["description"]);
     }

     public function setRouter() {
        $router = new Router($this->registry);
        $router->showContent();
        
        $this->DBH = null;
     }
 }

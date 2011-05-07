<?php
class View_Index {

	protected $registry;
    
    public $dateF;

	private $title = null;
	private $description = array();
	private $keywords = array();
    private $leftBlock = null;
	private $mainContent = null;

	private $menu = null;

	private $main;
	private $twig;


	function __construct($registry) {
		$this->registry = $registry;

        $this->main = $this->registry['layouts'];
        $this->twig = $this->registry['templates'];
	}

	function getTemplate($template) {
		$dirClass = explode("_", $template);
	
		if (sizeof($dirClass) > 1) {
			$template = implode(DIRECTORY_SEPARATOR, $dirClass) . '.tpl';
		} else
		{
			$template = $template . '.tpl';
		};
	
		return $template;
	}

	public function __call($name, $params) {
        $param = array("sitename" => $this->registry["siteName"], "uri" => $this->registry["uri"], "args" => $this->registry["args"]);
        
		$template = $this->twig->loadTemplate($this->getTemplate($name));

		if (isset($params[0])) {
			$content = $template->render($param + $params[0]);
		} else {
			$content = $template->render($param);
		};

		$this->setMainContent($content);
	}
	
	public function render($name, $params) {
        $param = array("sitename" => $this->registry["siteName"], "uri" => $this->registry["uri"], "args" => $this->registry["args"]);
        
		$template = $this->twig->loadTemplate($this->getTemplate($name));

		if (isset($params)) {
			$content = $template->render($param + $params);
		} else {
			$content = $template->render($param);
		};

		return $content;
	}
    
	public function setTitle($text) {
		$this->title .= $text;
	}

	public function setDescription($text) {
		$this->description[] = str_replace('"',"",$text);
	}

	public function setKeywords($text) {
		$this->keywords[] = str_replace('"',"",$text);
	}

	public function setMainContent($text) {
		$this->mainContent .= $text;
	}

	public function setLeftContent($text) {
		$this->leftBlock .= $text;
	}

	// Главная страница-шаблон
	public function showPage() {

		$template = $this->main->loadTemplate("head.tpl");
		$template->display(array("uri" => $this->registry["uri"],
                                "description" => implode(",", $this->description),
								"keywords" => implode(",", $this->keywords),
								"title" => $this->title));

		
		
        
        $categories = array();
        
        $i = 1;
        
		if ($this->registry["auth"]) {
			$categories[$i]["name"] = "Задачи";
			$categories[$i]["link"] = "tt";
            $i++;
            
			$categories[$i]["name"] = "Объекты";
			$categories[$i]["link"] = "objects";
            $i++;
            
			$categories[$i]["name"] = "Информация";
			$categories[$i]["link"] = "kb";
            $i++;            

			$categories[$i]["name"] = "Архив";
			$categories[$i]["link"] = "stat";
            $i++;
            
			$categories[$i]["name"] = "Поиск";
			$categories[$i]["link"] = "find";
            $i++;
            
            if ($this->registry["ui"]["admin"]) {
    			$categories[$i]["name"] = "Настройки";
    			$categories[$i]["link"] = "settings";
                $i++;
            }

		} else {
			$categories[$i]["name"] = "Главная";
			$categories[$i]["link"] = "";
            $i++;
          
			$categories[$i]["name"] = "Вход";
			$categories[$i]["link"] = "login";
            $i++;
		};

		for ($i=1; $i<=count($categories); $i++) {
            if ($this->registry["action"] == "index") {
                $action = "";
            } else {
                $action = $this->registry["action"];
            }

			if ($action == $categories[$i]["link"]) {
				$categories[$i]["selected"] = TRUE;
			} else {
				$categories[$i]["selected"] = FALSE;
			}
            
            if ($categories[$i]["link"] == "") {
                $categories[$i]["link"] = $this->registry["uri"] . $categories[$i]["link"];
            } else {
                $categories[$i]["link"] = $this->registry["uri"] . $categories[$i]["link"] . "/";
            }
		}

		$template = $this->main->loadTemplate("menu.tpl");

		$template->display(array("registry" => $this->registry,
                                "categories" => $categories,
                                "now" => $this->dateF));
       
        
              
		$template = $this->main->loadTemplate("content.tpl");
		$template->display(array("uri" => $this->registry["uri"],
                                "action" => $this->registry["action"],
                                "leftBlock" => $this->leftBlock,
                                "main_content" => $this->mainContent,
                                "ttgroups" => $this->registry["ttgroups"]));

		$template = $this->main->loadTemplate("footer.tpl");
		$template->display(array("uri" => $this->registry["uri"],
                                "sitename" => $this->registry["siteName"],
                                "ui" => $this->registry["ui"]));
	}
}
?>

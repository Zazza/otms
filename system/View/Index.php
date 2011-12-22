<?php
class View_Index extends Engine_View {

    public $leftBlock = null;
    private $content = null;
    private $profile;
    private $topMenu = null;
    private $fastmenu = null;
    private $fastmenu_notdrop = null;
    private $css = null;
    private $js = null;
    
    public function setLeftContent($text) {
        $this->leftBlock .= $text;
    }

	public function showPage() {
		if ($this->registry["ui"]["admin"]) {
			$this->setMenu(array("Система" => "Настройки"), "settings/");
		}
		$this->setMenu(array("Система" => "Справка"), "help/");
		$this->setMenu(array("Система" => "Выход"), "exit/");
		$this->setFastMenu(null, $this->render("fastmenu/find", array()), false);
		
		$menu = $this->renderMenu();
		$fastmenu = $this->renderFastMenu();
		
		$template = $this->main->loadTemplate($this->registry["ui"]["skin"] . "/layouts.html");
		$template->display(array("registry" => $this->registry,
		                                "description" => implode(",", $this->description),
										"keywords" => implode(",", $this->keywords),
										"title" => $this->title,
										"skinpath" => $this->registry["siteName"] . $this->registry["uri"] . "skins/" . $this->registry["ui"]["skin"] . "/",
										"css" => $this->css,
										"js" => $this->js,
										"menu" => $menu,
										"fastmenu" => $fastmenu,
										"fastmenu_notdrop" => $this->fastmenu_notdrop,
										"leftBlock" => $this->leftBlock,
                                		"main_content" => $this->mainContent,
										"content" => $this->content,
										"profile" => $this->profile));
	}
	
	public function addCSS($css) {
		$this->css .= '<link href="' . $css . '" rel="stylesheet" type="text/css" />';
	}
	
	public function addJS($js) {
		$this->js .= '<script type="text/javascript" src="' . $js . '"></script>';
	}
	
	private function renderMenu() {
		$menu = null;
		$settings = new Model_Settings();

		$setting = $this->registry["module_settings"];
		$sort = $settings->getMenu();
		
		if (count($sort) == 0) {
			$json[0]["content"] = null;
		}
		
		if (count($this->topMenu) > 0) {
			if ( (isset($sort)) and (count($sort) > 0) ) {
				foreach ($sort as $part) {
					foreach ($this->topMenu as $key=>$val) {
						if ($part == $key) {
							if (is_array($val)) {
								$menu .= $this->render("menu_sub", array("key" => $key, "val" => $val));
							} else {
								$menu .= $this->render("menu_one", array("key" => $key, "val" => $val));
							}
						}
					}
					
					foreach ($this->topMenu as $key=>$val) {
						$flag = false;
						foreach ($sort as $part) {
							if ($part == $key) {
								$flag = true;
							}
						}
							
						if (!$flag) {
							if (is_array($val)) {
								$menu .= $this->render("menu_sub", array("key" => $key, "val" => $val));
							} else {
								$menu .= $this->render("menu_one", array("key" => $key, "val" => $val));
							}
						}
					}
				}
			} else {
				foreach ($this->topMenu as $key=>$val) {
					if (is_array($val)) {
						$menu .= $this->render("menu_sub", array("key" => $key, "val" => $val));
					} else {
						$menu .= $this->render("menu_one", array("key" => $key, "val" => $val));
					}
				}
			}
		}
	
		return $menu;
	}
	
	public function setMenu($menu, $href) {
		$href = $this->registry["siteName"] . $this->registry["uri"] . $href;
		
		if (!is_array($menu)) {
			$this->topMenu[$menu] = $href;
		} else {
			foreach($menu as $key=>$val) {
				$this->topMenu[$key][$val] = $href;
			}
		} 
	}
	
	public function renderFastMenu() {
		$setting = new Model_Settings();

		$sort = $setting->getFastmenu();
		
		if (count($sort) == 0) {
			$json[0]["content"] = null;
		}

		if ( (!isset($sort)) or (count($sort) == 0) ) { $sort = array(); }
		
		$temp_fastmenu = $this->fastmenu;
		$this->fastmenu = array();
		if (count($temp_fastmenu) > 0) {
			foreach ($sort as $part) {
				foreach ($temp_fastmenu as $key=>$val) {
					if ($part == $key) {
						$this->fastmenu[] = $val;
					}
				}
			}
			
			foreach ($temp_fastmenu as $key=>$val) {
				$flag = false;
				foreach ($sort as $part) {
					if ($part == $key) {
						$flag = true;
					}
				}
				
				if (!$flag) {
					$this->fastmenu[] = $val;
				}
			}
		}
		
		return implode(" ", $this->fastmenu);
	}
	
	public function setFastMenu($name, $content, $notdrop) {
		if ($notdrop) {
			$this->fastmenu[$name] = "<li class='topmenubutton'>" . $content . "</li>";
		} else {
			$this->fastmenu_notdrop .= $content;
		}
	}
	
	public function setContent($content) {
		$this->content .= $content;
	}
	
	public function setProfile($content) {
		$this->profile = $content;
	}
}
?>

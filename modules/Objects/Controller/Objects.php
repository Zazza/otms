<?php
class Controller_Objects extends Modules_Controller {
	protected $find = null;
	protected $tree = array();
	protected $templates;
	protected $tree_depth = array();
	protected $object = null;
	protected $depth = array();
	
	protected $mtemplate;
	
	protected function print_array($arr) {
		if (!is_array($arr)) {
			return;
		}
	
		while(list($key, $val) = each($arr)) {
			if (!is_array($val)) {
				if ($val == null) {
					$val = "пусто";
				}
			}
			if (is_array($val)) {
				if ($key != "0") {
					if(is_numeric($key)) {
						$tname = $this->mtemplate->getCatName($key);
						$this->find .= "<ul><li><span class='folder'>&nbsp;<a href='" . $this->registry["uri"] . "objects/sub/" . $key . "/'>" . $tname["name"] . "</a>&nbsp;<a href='" . $this->registry["uri"] . "objects/add/?p=" . $key . "' title='добавить объект'><img border='0' style='position: relative; top: 3px; margin-left: 4px' alt='plus' src='" . $this->registry["uri"] . "img/plus-small.png' /></a></span>";
					} else {
						$this->find .= "<ul><li><span class='folder'>&nbsp;" . $key . "</span>";
					}
				}
	
				$this->print_array($val);
	
				if ($key != "0") {
					$this->find .= "</li></ul>";
				}
			}
		}
	}

	public function index() {
		$this->view->setLeftContent($this->view->render("left_objects", array()));
		
		$this->object = new Model_Object();
		$this->mtemplate = new Model_Template();
		
		$templates = $this->mtemplate->getTemplates();
		$this->templates = $templates;
		
		foreach($templates as $part) {
			$this->tree[$part["id"]] = $this->mtemplate->getTree($part["id"]);
		}
		
		if (isset($this->args[0])) {
			if ($this->args[0] == "add") {

				Controller_Objects_Add::index();

			} elseif ($this->args[0] == "list") {

				Controller_Objects_List::index();

			} elseif ($this->args[0] == "info") {

				Controller_Objects_Info::index();

			} elseif ($this->args[0] == "edit") {

				Controller_Objects_Edit::index();
				
			} elseif ($this->args[0] == "history") {

				Controller_Objects_History::index();

			} elseif ($this->args[0] == "admin") {
			
				Controller_Objects_Admin::index();

			} elseif ($this->args[0] == "templates") {

				Controller_Objects_Templates::index();

			} elseif ($this->args[0] == "sub") {
				
				Controller_Objects_Subgroups::index();
				
			} elseif ($this->args[0] == "forms") {
			
				Controller_Objects_Forms::index();
				
			} elseif ($this->args[0] == "setform") {
					
				Controller_Objects_Setform::index();
			
			} else {

				Controller_Objects_Show::index();

			}
		} else {

			Controller_Objects_List::index();

		}
	}
}
?>
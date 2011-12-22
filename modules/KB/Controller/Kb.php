<?php
class Controller_Kb extends Modules_Controller {
	protected $tree;
	
	protected function print_array($arr) {
		if (!is_array($arr)) {
			return;
		}
	
		while(list($key, $val) = each($arr)) {
			if (!is_array($val)) {
				if ($val == null) {
					$val = "пусто";
				}
	
				$this->tree .= "<ul><li><div style='margin: 0 0 0 10px'><a href='" . $this->registry["uri"] . "kb/?tag=" . $val . "'>" . $val . "</a></div></li></ul>";
			}
			if (is_array($val)) {
				if ($key != "0") {
					$this->tree .= "<ul><li><span class='folder'>&nbsp;" . $key . "</span>";
				}
	
				$this->print_array($val);
	
				if ($key != "0") {
					$this->tree .= "</li></ul>";
				}
			}
		}
	}

	public function index() {
		$this->view->setLeftContent($this->view->render("left_kb", array()));
		
		if (isset($this->args[0])) {
			if ($this->args[0] == "add") {
				Controller_Kb_Add::index();
			} else {
				Controller_Kb_Kb::index();
			}
		} else {
			Controller_Kb_Kb::index();
		}
	}
}
?>
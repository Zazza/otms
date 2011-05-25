<?php
class Controller_Kb extends Controller_Index {
    private $tree = null;
    
	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
    
    private function print_array($arr) {
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
	
	public function index($args) {
        if ($this->registry["auth"]) {
            $this->view->setTitle("Теги");
           
            $advinfo = new Model_Ai($this->registry);
            $tpl = new Model_Template($this->registry);
            
            if (isset($_GET["tag"])) {
                $ai = $advinfo->getAIFromTag($_GET["tag"]);
                
                $this->view->tags_ai(array("tag" => $_GET["tag"], "ai" => $ai, "ui" => $this->registry["ui"]));
            } else {
                $templates = $tpl->getTemplates();
                $list = $advinfo->getAi();
                
                $sortlist = array();
                foreach($templates as $template) {
                    foreach($list as $kb) {
                        if ($kb["name"] == $template["name"]) {
                            $sortlist[$template["name"]][] = $kb["tag"];
                        }
                    }
                }
                
                $this->print_array($sortlist);
                
                $this->view->kb_tree(array("list" => $this->tree));
            }
            
           
            $this->view->showPage();
        }
    }
}
?>
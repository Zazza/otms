<?php
class Controller_Settings_Templates extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "settings", "templates");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if ($this->registry["ui"]["admin"]) {
            
                $this->view->setTitle("Шаблоны");
                
                $this->view->setLeftContent($this->view->render("left_settings", array()));
                
                $task = new Model_Task($this->registry);
                $list = $task->getTemplates();
                
                if (isset($args[1])) {
                    if ($args[1] == "add") {
                        if (isset($_POST["submit"])) {
                            $task->addTemplate($_POST);
                            
                            $this->view->refresh(array("timer" => "1", "url" => "/settings/templates/"));
                        } else {
                            $this->view->settings_templateadd();
                        }
                    } elseif ($args[1] == "edit") {
                        if (isset($args[2])) {
                            if (isset($_POST["submit"])) {
                                $task->editTemplate($args[2], $_POST);
                                
                                $this->view->refresh(array("timer" => "1", "url" => "/settings/templates/"));
                            } else {
                                $param = $task->getTemplate($args[2]);
                                $this->view->settings_templateedit(array("post" => $param));
                            }
                        }
                    }
                } else {
                    $this->view->settings_templates(array("list" => $list));
                }
            }
        }
        
        $this->view->showPage();
	}
}
?>
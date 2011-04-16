<?php
class Controller_Settings_Kb extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "settings", "kb");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if ($this->registry["ui"]["admin"]) {
            
                $this->view->setTitle("Редактор групп \"KB\"");
    
                $this->view->setLeftContent($this->view->render("left_settings", array()));
                
                if (isset($args[1])) {
                    if ($args[1] == "addgroup") {
                        $this->view->settings_addgroupkb();
                    } elseif ($args[1] == "edit") {
                        if (isset($args[2])) {
                    
                            if(isset($_POST['submit_group'])) {
                                $this->kb->editGroupName($args[2], $_POST["group"]);
                                
                                $this->view->refresh(array("timer" => "1", "url" => "/settings/kb/"));
                            } else {
                            
                                $name = $this->kb->getGroupName($args[2]);
                            
                                $this->view->settings_editgroupkb(array("id" => $args[2], "name" => $name));
                            }
                        }
                    }
                } else {
                    if (isset($_POST['submit_group'])) {
                        $this->kb->addGroups($_POST["group"]);
                    }
                    
                    $groups = $this->kb->getGroups();
                    
                    $this->view->settings_kb(array("group" => $groups));
                }
            }
        }
                
        $this->view->showPage();
	}
}
?>
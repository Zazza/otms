<?php
class Controller_Settings_Tt extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "settings", "tt");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if ($this->registry["ui"]["admin"]) {
            
                $this->view->setTitle("Редактор групп \"HelpDesk\"");
    
                $this->view->setLeftContent($this->view->render("left_settings", array()));
        	   
                if (isset($args[1])) {
                    if ($args[1] == "addgroup") {
                        $this->view->settings_addgrouptt();
                    } elseif ($args[1] == "edit") {
                        if (isset($args[2])) {
                    
                            if(isset($_POST['submit_group'])) {
                                $this->tt->editGroupName($args[2], $_POST["group"]);
                                
                                $this->view->refresh(array("timer" => "1", "url" => "settings/tt/"));
                            } else {
                            
                                $name = $this->tt->getGroupName($args[2]);
                            
                                $this->view->settings_editgrouptt(array("id" => $args[2], "name" => $name));
                            }
                        }
                    }
                } else {
                    if (isset($_POST['submit_group'])) {
                        $this->tt->addGroups($_POST["group"]);
                    }
                    
                    $groups = $this->tt->getGroups();
                    
                    $this->view->settings_tt(array("group" => $groups));
                }
            }
        }
                
        $this->view->showPage();
	}
}
?>
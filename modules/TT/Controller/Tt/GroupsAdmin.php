<?php
class Controller_Tt_GroupsAdmin extends Controller_Tt {
	
	public function index() {
        if ($this->registry["ui"]["admin"]) {
        	
        	$this->view->setTitle("Группы задач");

	        if (isset($this->args[1])) {
            	if($this->args[1] == "add") {
            	   	if (isset($_POST['submit_group'])) {
	            	    $this->registry["tt"]->addGroups($_POST["group"]);
	                
	                	$this->view->refresh(array("timer" => "1", "url" => "tt/groups/"));
	        		} else {
	        			$this->view->groups_addgrouptt();
	        		}
                } elseif ($this->args[1] == "edit") {
                	if (isset($this->args[2])) {
                    
                    	if(isset($_POST['submit_group'])) {
                        	$this->registry["tt"]->editGroupName($this->args[2], $_POST["group"]);
                                
                            $this->view->refresh(array("timer" => "1", "url" => "tt/groups/"));
                        } else {
                            
                        	$name = $this->registry["tt"]->getGroupName($this->args[2]);
                            
                            $this->view->groups_editgrouptt(array("id" => $this->args[2], "name" => $name));
                        }
                    }
                }
            }
        }
        
        $this->view->showPage();
	}
}
?>
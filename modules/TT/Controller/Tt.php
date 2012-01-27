<?php
class Controller_Tt extends Modules_Controller {

	public function index() {
		$this->registry->set("getNumMeTasks", $this->registry["tt"]->getNumMeTasks());
		$this->registry->set("getNumTasks", $this->registry["tt"]->getNumTasks());
		$this->registry->set("draftttnum", $this->registry["tt"]->getDraftNumTasks($this->registry["ui"]["id"]));
		
		$this->view->setLeftContent($this->view->render("left_tt", array()));
		
        if (isset($this->args[0])) {
            if ($this->args[0] == "add") {

                Controller_Tt_Add::index();
                
            } elseif ($this->args[0] == "task") {

                Controller_Tt_Index::index();
                
            } elseif ($this->args[0] == "list") {

                Controller_Tt_List::index();
                
            } elseif ($this->args[0] == "date") {

                Controller_Tt_Index::index();
             
            } elseif ($this->args[0] == "oid") {

                Controller_Tt_Index::index();
             
            } elseif ($this->args[0] == "cal") {

                Controller_Tt_Cal::index();

            }  elseif ($this->args[0] == "page") {
                
                Controller_Tt_Index::index();
                
            }  elseif ($this->args[0] == "edit") {

                Controller_Tt_Edit::index();
                
            }  elseif ($this->args[0] == "groups") {

                Controller_Tt_Groups::index();

            }  elseif ($this->args[0] == "groups-admin") {

                Controller_Tt_GroupsAdmin::index();
                
            }  elseif ($this->args[0] == "history") {

                Controller_Tt_History::index();
                
                
            }  elseif ($this->args[0] == "draft") {

                Controller_Tt_Draft::index();

           }  elseif ($this->args[0] == "draftedit") {

                Controller_Tt_Draftedit::index();
           
           }  elseif ($this->args[0] == "attach") {

                Controller_Tt_Attach::index();
                    
            } else {

                Controller_Tt_Show::index();
                
            }
        } else {
        	Controller_Tt_Index::index();
        }
    }
}
?>
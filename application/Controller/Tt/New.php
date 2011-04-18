<?php
class Controller_Tt_New extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "new");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if (!$this->registry["ui"]["readonly"]) {
            
                $this->view->setTitle("Новые задачи");
                
                $this->view->setLeftContent($this->view->render("left_tt", array("ui" => $this->registry["ui"])));
                
                $tasks = $this->tt->getNobodyTasks();
                
                foreach($tasks as $part) {
                    if ($data = $this->tt->getTask($part["id"])) {
                        $numComments = $this->tt->getNumComments($part["id"]);
                        
                        $author = $this->user->getUserInfo($data[0]["who"]);
                        
                        foreach($data as $val) {
                            $ruser[] = $this->user->getUserInfo($val["uid"]);
                        }
                        
                        $task = new Model_Task($this->registry);
                        
                        $obj = $task->getShortObject($data[0]["oid"]);
                        
                        $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "notObj" => true, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
                        
                        unset($ruser);
                    } else {
                        $this->view->setMainContent("<p>Задача не найдена</p>");
                    }   
                }
            }
        }
        
        $this->view->showPage();
	}
}
?>
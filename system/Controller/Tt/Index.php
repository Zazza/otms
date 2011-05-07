<?php
class Controller_Tt_Index extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "index");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Задачи");
            
            $task = new Model_Task($this->registry);
            
            $this->view->setLeftContent($this->view->render("left_tt", array("ui" => $this->registry["ui"], "menu" => $args[1])));

            if (isset($args[2])) {
    			if ( ($args[2] == "page") and (isset($args[3])) ) {
    				if (!$this->tt->setPage($args[3])) {
    					$this->__call("tt", "index");
    				}
    			}
    		}
           
            $this->tt->links = "/" . $args[0] . "/" . $args[1];
           
            if (isset($args[0])) {
                if ($args[0] == "task") {
                    if ($args[1] == "allmy") {
                        $tasks = $this->tt->getTasksWithoutMe($this->registry["ui"]["id"]);
                    } elseif($args[1] == "all") {
                        $tasks = $this->tt->getAllTasks();
                    } elseif($args[1] == "iter") {
                        $tasks = $this->tt->getIterTasks($this->registry["ui"]["id"]);
                    } elseif($args[1] == "time") {
                        $tasks = $this->tt->getTimeTasks($this->registry["ui"]["id"]);
                    } elseif($args[1] == "noiter") {
                        $tasks = $this->tt->getNoiterTasks($this->registry["ui"]["id"]);
                    } elseif($args[1] == "me") {
                        $tasks = $this->tt->getMeTasks($this->registry["ui"]["id"]);
                    } else {
                        $tasks = $this->tt->getTasks($this->registry["ui"]["id"]);
                    }
                } elseif($args[0] == "date") {
                    $this->view->setMainContent("<div style='font-size: 18px; margin-bottom: 20px'>" . $this->model->editDate(date("Y-m-d", strtotime($args[1]))) . "</div>");
                    $tasks = $this->tt->getTasksDate($this->registry["ui"]["id"], $args[1]);
                } elseif($args[0] == "oid") {
                    $tasks = $this->tt->getOidTasks($args[1]);
                }
            } else {
                $tasks = $this->tt->getTasks($this->registry["ui"]["id"]);
            }

            if (count($tasks) == 0) {
                $this->view->setMainContent("<p>Задачи не найдены</p>");
            }
            
            if (!isset($args[2]) or ($args[2] == "page"))  {
                
                foreach($tasks as $part) {
    
                    if ($data = $this->tt->getTask($part["id"])) {
                        $numComments = $this->tt->getNumComments($part["id"]);
                        
                        $author = $this->user->getUserInfo($data[0]["who"]);
                        
                        foreach($data as $val) {
                            $ruser[] = $this->user->getUserInfo($val["uid"]);
                        }

                        $obj = $task->getShortObject($data[0]["oid"]);
                        
                        $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "notObj" => true, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
 
                        unset($ruser);
                    } else {
                        $this->view->setMainContent("<p>Задача не найдена</p>");
                    }
                }
                
                //Отобразим пейджер
    			if (count($this->tt->pager) != 0) {
    				$this->view->pager(array("pages" => $this->tt->pager));
    			}
                
            }
        }
           
        $this->view->showPage();
	}
}
?>
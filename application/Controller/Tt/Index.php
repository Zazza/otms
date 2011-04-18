<?php
class Controller_Tt_Index extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "index");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Задачи");
            
            $this->view->setLeftContent($this->view->render("left_tt", array("ui" => $this->registry["ui"])));
            
            if (isset($args[0])) {
    			if ( ($args[0] == "page") and (isset($args[1])) ) {
    				if (!$this->tt->setPage($args[1])) {
    					$this->__call("tt", "index");
    				}
    			}
    		}
            
            if (isset($_GET["task"])) {
                if ($_GET["task"] == "allmy") {
                    $tasks = $this->tt->getTasksWithoutMe($this->registry["ui"]["id"]);
                } elseif($_GET["task"] == "all") {
                    $tasks = $this->tt->getAllTasks();
                } elseif($_GET["task"] == "iter") {
                    $tasks = $this->tt->getIterTasks($this->registry["ui"]["id"]);
                } elseif($_GET["task"] == "time") {
                    $tasks = $this->tt->getTimeTasks($this->registry["ui"]["id"]);
                } elseif($_GET["task"] == "noiter") {
                    $tasks = $this->tt->getNoiterTasks($this->registry["ui"]["id"]);
                } elseif($_GET["task"] == "me") {
                    $tasks = $this->tt->getMeTasks($this->registry["ui"]["id"]);
                } else {
                    $tasks = $this->tt->getTasks($this->registry["ui"]["id"]);
                }       
            } elseif(isset($_GET["date"])) {
                $this->view->setMainContent("<h3>" . $this->model->editDate(date("Y-m-d", strtotime($_GET["date"]))) . "</h3>");
                $tasks = $this->tt->getTasksDate($this->registry["ui"]["id"], $_GET["date"]);
            } elseif(isset($_GET["oid"])) {
                $tasks = $this->tt->getOidTasks($_GET["oid"]);
            } else {
                $tasks = $this->tt->getTasks($this->registry["ui"]["id"]);
            }

            if (count($tasks) == 0) {
                $this->view->setMainContent("<p>Задачи не найдены</p>");
            }
            
            if (!isset($args[0]) or ($args[0] == "page"))  {
                
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
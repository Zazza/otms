<?php
class Controller_Tt_Index extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "index");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Задачи");
            
            $object = new Model_Object($this->registry);
            
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
                        $tasks = $this->tt->getTasksAllMe();
                    } elseif($args[1] == "all") {
                        $tasks = $this->tt->getAllTasks();
                    } elseif($args[1] == "iter") {
                        $tasks = $this->tt->getIterTasks();
                    } elseif($args[1] == "time") {
                        $tasks = $this->tt->getTimeTasks();
                    } elseif($args[1] == "noiter") {
                        $tasks = $this->tt->getNoiterTasks();
                    } elseif($args[1] == "me") {
                        $tasks = $this->tt->getMeTasks();
                    } else {
                        $tasks = $this->tt->getTasks();
                    }
                } elseif($args[0] == "date") {
                    $this->view->setMainContent("<div class='title'>" . $this->model->editDate(date("Y-m-d", strtotime($args[1]))) . "</div>");
                    $tasks = $this->tt->getTasksDate($this->registry["ui"]["id"], $args[1]);
                } elseif($args[0] == "oid") {
                    $tasks = $this->tt->getOidTasks($args[1]);
                }
            } else {
                $tasks = $this->tt->getTasks();
            }

            if (count($tasks) == 0) {
                $this->view->setMainContent("<p>Задачи не найдены</p>");
            }
            
            if (!isset($args[2]) or ($args[2] == "page"))  {
                
                foreach($tasks as $part) {
    
                    if ($data = $this->tt->getTask($part["id"])) {
                        $numComments = $this->tt->getNumComments($part["id"]);
                        
                        $author = $this->user->getUserInfo($data[0]["who"]);
                        
                        $ruser = array();
                        
                        foreach($data as $val) {
                            if (isset($val["uid"])) {
                                if ($val["uid"] != 0) {
                                    $ruser[] = $this->user->getUserInfo($val["uid"]);
                                }
                            }
                            
                            if (isset($val["rgid"])) {
                                if ($val["rgid"] != 0) {
                                    $ruser[]["name"] = "<span style='color: #5D7FA6'><b>" . $this->user->getGroupName($val["rgid"]) . "</b></span>";
                                }
                            }
                            
                            if ($val["all"] == 1) {
                                $ruser[]["name"] = "<span style='color: #D9A444'><b>Все</b></span>";
                            }
                        }
                        
                        $cuser = $this->user->getUserInfo($data[0]["cuid"]);

                        $obj = $object->getShortObject($data[0]["oid"]);
                        
                        $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "cuser" => $cuser, "notObj" => true, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
 
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
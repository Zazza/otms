<?php
class Controller_Find_Tasks extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "find", "objects");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Поиск");
           
            $find = new Model_Find($this->registry);
            $task = new Model_Task($this->registry);
            
            $findSess = & $_SESSION["find"];
            
            if (isset($_POST["find"])) {
                $_POST["find"] = htmlspecialchars($_POST["find"]);
                $findSess["string"] = $_POST["find"];
            } else {
                if (!isset($findSess["string"])) {
                    $findSess["string"] = "";
                }
            }
            
            $tfind = explode(" ", substr($findSess["string"], 0, 64));
            $this->view->setLeftContent($this->view->render("left_find", array("num" => $find->getNumFinds($tfind))));
            
            if (isset($findSess["string"])) {
                
                $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $findSess["string"] . "</p>");
    
                if (isset($args[1])) {
        			if ( ($args[1] == "page") and (isset($args[2])) ) {
        				if (!$find->setPage($args[2])) {
        					$this->__call("objects", "index");
        				}
        			}
        		}
                
                $text = substr($findSess["string"], 0, 64);
    			$text = explode(" ", $text);
    
                $findArr = $find->findTroubles($text);
                
                if (!isset($args[1]) or ($args[1] == "page"))  {
                    
                    foreach($findArr as $part) {
                        
                        if ($data = $this->tt->getTask($part["id"])) {
                            
                            $numComments = $this->tt->getNumComments($part["id"]);
                            
                            $author = $this->user->getUserInfo($data[0]["who"]);
                            
                            foreach($data as $val) {
                                $ruser[] = $this->user->getUserInfo($val["uid"]);
                            }
                        
                            $obj = $task->getShortObject($part["oid"]);
                            
                            $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "notObj" => true, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
                        }
                    }
                
                    //Отобразим пейджер
        			if (count($find->pager) != 0) {
        				$this->view->pager(array("pages" => $find->pager));
        			}
                }
            }
        }
        $this->view->showPage();
    }
}
?>
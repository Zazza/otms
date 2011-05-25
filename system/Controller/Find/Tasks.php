<?php
class Controller_Find_Tasks extends Controller_Find {
    
    public function __construct($registry) {
		parent::__construct($registry);
        
        $this->begin("find", "tasks");
	}
	
	public function index($args) {
        $this->view->setTitle("Поиск");
       
        $find = new Model_Find($this->registry);
        $object = new Model_Object($this->registry);
        
        if (isset($this->findSess["string"])) {
            
            $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $this->findSess["string"] . "</p>");

            if (isset($args[1])) {
    			if ( ($args[1] == "page") and (isset($args[2])) ) {
    				if (!$find->setPage($args[2])) {
    					$this->__call("objects", "index");
    				}
    			}
    		}
            
            $text = substr($this->findSess["string"], 0, 64);
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
                    
                        $obj = $object->getShortObject($part["oid"]);
                        
                        $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "notObj" => true, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
                    }
                }
            
                //Отобразим пейджер
    			if (count($find->pager) != 0) {
    				$this->view->pager(array("pages" => $find->pager));
    			}
            }
        }

        $this->view->showPage();
    }
}
?>
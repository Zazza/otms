<?php
class Controller_Find_Tasks extends Controller_Find {

	public function index() {

        $this->view->setTitle("Поиск");
       
        $find = new Model_Find();
        $object = new Model_Object();
        
        if (isset($this->findSess["string"])) {
            
            $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $this->findSess["string"] . "</p>");

        	if (isset($_GET["page"])) {
    			if (is_numeric($_GET["page"])) {
    				if (!$find->setPage($_GET["page"])) {
    					$this->__call("find", "tasks");
    				}
    			}
    		}
    		
    		$find->links = "/" . $this->args[0] . "/";
            
            $text = substr($this->findSess["string"], 0, 64);
			$text = explode(" ", $text);

            $findArr = $find->findTroubles($text);
            
            if (!isset($this->args[1]) or ($this->args[1] == "page"))  {
                
                foreach($findArr as $part) {
                    
                    if ($data = $this->registry["tt"]->getTask($part["id"])) {
                        
                        $numComments = $this->registry["tt"]->getNumComments($part["id"]);
                        
                        $author = $this->registry["user"]->getUserInfo($data[0]["who"]);
                        
                        foreach($data as $val) {
                            $ruser[] = $this->registry["user"]->getUserInfo($val["uid"]);
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
    }
}
?>
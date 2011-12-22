<?php
class Controller_Find_Adv extends Controller_Find {

	public function index() {
		
        $this->view->setTitle("Поиск");
       
        $find = new Model_Find();
        $object = new Model_Object();
        $ai = new Model_Ai();
        
        if (isset($this->findSess["string"])) {
            
            $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $this->findSess["string"] . "</p>");

            if (isset($this->args[1])) {
    			if ( ($this->args[1] == "page") and (isset($this->args[2])) ) {
    				if (!$find->setPage($this->args[2])) {
    					$this->__call("objects", "index");
    				}
    			}
    		}
    		
    		$find->links = "/" . $this->args[0] . "/";
            
            $text = substr($this->findSess["string"], 0, 64);
			$text = explode(" ", $text);

            $findArr = $find->findAdvs($text);
            
            if (!isset($this->args[1]) or ($this->args[1] == "page"))  {
                
                foreach($findArr as $part) {
                    
                    $numTroubles = $object->getNumTroubles($part["id"]);
                    $obj = $object->getShortObject($part["id"]);
                    $advInfo = $ai->getAdvancedInfo($part["id"]);
                    $numAdvInfo = $ai->getNumAdvancedInfo($part["id"]);
                    $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
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
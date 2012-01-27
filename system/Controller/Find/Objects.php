<?php
class Controller_Find_Objects extends Controller_Find {

	public function index() {
        $this->view->setTitle("Поиск");

        $object = new Model_Object();
        $ai = new Model_Ai();
        $forms = $ai->getForms();

        if (isset($this->findSess["string"])) {
            
            $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $this->findSess["string"] . "</p>");

        	if (isset($_GET["page"])) {
    			if (is_numeric($_GET["page"])) {
    				if (!$this->find->setPage($_GET["page"])) {
    					$this->__call("find", "objects");
    				}
    			}
    		}
    		
    		$this->find->links = "/" . $this->args[0] . "/";
            
            $text = substr($this->findSess["string"], 0, 64);
			$text = explode(" ", $text);

            $findArr = $this->find->findObjects($text);
            
            if (!isset($this->args[1]) or ($this->args[1] == "page"))  {
                
                foreach($findArr as $part) {
                    
                    $numTroubles = $object->getNumTroubles($part["id"]);
                    $obj = $object->getShortObject($part["id"]);
                    $advInfo = $ai->getAdvancedInfo($part["id"]);
                    $numAdvInfo = $ai->getNumAdvancedInfo($part["id"]);
                    $this->view->find_objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "forms" => $forms, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
                }
            
                //Отобразим пейджер
    			if (count($this->find->pager) != 0) {
    				$this->view->pager(array("pages" => $this->find->pager));
    			}
            }
        }
    }
}
?>
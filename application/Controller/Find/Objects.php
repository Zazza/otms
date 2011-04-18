<?php
class Controller_Find_Objects extends Controller_Index {
    
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
    
                $findArr = $find->findObjects($text);
                
                if (!isset($args[1]) or ($args[1] == "page"))  {
                    
                    foreach($findArr as $part) {
                        
                        $numTroubles = $task->getNumTroubles($part["id"]);
                        $obj = $task->getShortObject($part["id"]);
                        $advInfo = $task->getAdvancedInfo($part["id"]);
                        $numAdvInfo = $task->getNumAdvancedInfo($part["id"]);
                        $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
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
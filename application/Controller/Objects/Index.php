<?php
class Controller_Objects_Index extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "objects", "index");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Объекты");
            
            $this->view->setLeftContent($this->view->render("left_objects", array()));
            
            $task = new Model_Task($this->registry);
            
            $findSess = & $_SESSION["find"];
            
            if (isset($_POST["find"])) {
                $findSess["string"] = $_POST["find"];
            } else {
                if (!isset($findSess["string"])) {
                    $findSess["string"] = "";
                }
            }
            
            if (isset($findSess["string"])) {
                
                $this->view->setMainContent("<p style='font-weight: bold; margin-bottom: 20px'>Поиск: " . $findSess["string"] . "</p>");
    
                if (isset($args[0])) {
        			if ( ($args[0] == "page") and (isset($args[1])) ) {
        				if (!$task->setPage($args[1])) {
        					$this->__call("objects", "index");
        				}
        			}
        		}
                
                $text = substr($findSess["string"], 0, 64);
    			$text = explode(" ", $text);
    
                $find = $task->findObjects($text);
                
                if (!isset($args[0]) or ($args[0] == "page"))  {
                    
                    foreach ($find as $part) {
                        
                        $numTroubles = $task->getNumTroubles($part["id"]);
                        $obj = $task->getShortObject($part["id"]);
                        $advInfo = $task->getAdvancedInfo($part["id"]);
                        $numAdvInfo = $task->getNumAdvancedInfo($part["id"]);
                        $this->view->objectMain(array("obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
                    }
                
                    //Отобразим пейджер
        			if (count($task->pager) != 0) {
        				$this->view->pager(array("pages" => $task->pager));
        			}
                }
            }
        }
        
        $this->view->showPage();
	}
}
?>
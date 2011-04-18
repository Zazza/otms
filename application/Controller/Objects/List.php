<?php
class Controller_Objects_List extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "objects", "list");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Просмотр");
       
            $this->view->setLeftContent($this->view->render("left_objects", array("ui" => $this->registry["ui"])));
            
            $task = new Model_Task($this->registry);
    
            $clauseSess = & $_SESSION["clause"];
            
            if (isset($_POST["submit"])) {

                $_POST["templates"] = htmlspecialchars($_POST["templates"]);
                $_POST["criterion"] = htmlspecialchars($_POST["criterion"]);
                $_POST["sday"] = htmlspecialchars($_POST["sday"]);
                $_POST["smonth"] = htmlspecialchars($_POST["smonth"]);
                $_POST["syear"] = htmlspecialchars($_POST["syear"]);
                $_POST["fday"] = htmlspecialchars($_POST["fday"]);
                $_POST["fmonth"] = htmlspecialchars($_POST["fmonth"]);
                $_POST["fyear"] = htmlspecialchars($_POST["fyear"]);

                $clauseSess["string"] = $_POST;
            } else {
                if (!isset($clauseSess["string"])) {
                    $clauseSess["string"] = "";
                }
            }
    
            $templates = $task->getTemplates();
            
            if (isset($clauseSess["string"]["templates"])) { $template = $clauseSess["string"]["templates"]; } else { $template = 0; }      
            if (isset($clauseSess["string"]["criterion"])) { $criterion = $clauseSess["string"]["criterion"]; } else { $criterion = 0; }
            if (isset($clauseSess["string"]["sday"])) { $sday = $clauseSess["string"]["sday"]; } else { $sday = "01"; }
            if (isset($clauseSess["string"]["smonth"])) { $smonth = $clauseSess["string"]["smonth"]; } else { $smonth = "01"; }
            if (isset($clauseSess["string"]["syear"])) { $syear = $clauseSess["string"]["syear"]; } else { $syear = "2000"; }
            if (isset($clauseSess["string"]["fday"])) { $fday = $clauseSess["string"]["fday"]; } else { $fday = date("d"); }
            if (isset($clauseSess["string"]["fmonth"])) { $fmonth = $clauseSess["string"]["fmonth"]; } else { $fmonth = date("m"); }
            if (isset($clauseSess["string"]["fyear"])) { $fyear = $clauseSess["string"]["fyear"]; } else { $fyear = date("Y"); }
            
            $this->view->objects_list(array("list" => $templates, "template" => $template, "criterion" => $criterion, "sday" => $sday, "smonth" => $smonth, "syear" => $syear, "fday" => $fday, "fmonth" => $fmonth, "fyear" => $fyear));
    
    
            if (isset($clauseSess["string"]["submit"])) {
                
                $this->view->setMainContent("<p style='margin-bottom: 20px'><b>Последняя выборка</b></p>");
                
                $task->links = "/list";
                
                if (isset($args[1])) {
        			if ( ($args[1] == "page") and (isset($args[2])) ) {
        				if (!$task->setPage($args[2])) {
        					$this->__call("objects", "list");
        				}
        			}
        		}
                
                $data = $task->getObjectsByClause($clauseSess["string"]);
                
                if (!isset($args[1]) or ($args[1] == "page"))  {
                
                    foreach($data as $part) {
                        if ($obj = $task->getShortObject($part["id"])) {
                            
                            $numTroubles = $task->getNumTroubles($part["id"]);                        
                            $advInfo = $task->getAdvancedInfo($part["id"]);
                            $numAdvInfo = $task->getNumAdvancedInfo($part["id"]);
                            $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
                        } else {
                            $this->view->setMainContent("<p>Объект не найден</p>");
                        }
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
<?php
class Controller_Objects_List extends Controller_Objects {
    private $find = null;
    private $tree = array();
    private $templates;
    private $tree_depth = array();
    private $object = null;
    private $depth = array();
    
    private $mtemplate;
    
    public function __construct($registry) {
		parent::__construct($registry);
        
        $this->begin("objects", "list");
        
        $this->object = new Model_Object($this->registry);
        $this->mtemplate = new Model_Template($this->registry);
        
        $templates = $this->mtemplate->getTemplates();
        $this->templates = $templates;
        
        foreach($templates as $part) {
            $this->tree[$part["id"]] = $this->mtemplate->getTree($part["id"]);
        }
	}
    
    private function print_array($arr) {
        if (!is_array($arr)) {
            return;
        }
        
        while(list($key, $val) = each($arr)) {
            if (!is_array($val)) {
                if ($val == null) {
                    $val = "пусто";
                }
                
                $this->find .= "<ul><li><div style='margin: 0 0 0 10px'>" . $val . "</div></li></ul>";
            }
            if (is_array($val)) {
                if ($key != "0") {
                    if(is_numeric($key)) {
                        $tname = $this->mtemplate->getCatName($key);
                        $this->find .= "<ul><li><span class='folder'>&nbsp;" . $tname . "&nbsp;<a href='" . $this->registry["uri"] . "objects/add/?p=" . $key . "' title='добавить объект'><img border='0' style='position: relative; top: 3px; margin-left: 4px' alt='plus' src='" . $this->registry["uri"] . "img/plus-small.png' /></a></span>";
                    } else {
                        $this->find .= "<ul><li><span class='folder'>&nbsp;" . $key . "</span>";
                    }
                }
                
                $this->print_array($val);
                
                if ($key != "0") {
                    $this->find .= "</li></ul>";
                }
            }
        }
    }
    
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setTitle("Просмотр");
            
            if (isset($_GET["clear"])) {
                unset($_SESSION["clause"]);
            }
            
            $clauseSess = & $_SESSION["clause"];
      
            if (isset($_POST["move_confirm"])) {
                if (isset($_POST["obj"])) {
                    foreach($_POST["obj"] as $key => $val) {
                        if ($this->mtemplate->getTidFromPid($key) == $_POST["tName"]) {
                            $this->object->moveObj($key, $_POST["tTypeName"]);
                        }
                    }
                }
            }
            
            if (isset($_POST["submit"])) {

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

            if (isset($clauseSess["string"]["criterion"])) { $criterion = $clauseSess["string"]["criterion"]; } else { $criterion = 0; }
            if (isset($clauseSess["string"]["sday"])) { $sday = $clauseSess["string"]["sday"]; } else { $sday = "01"; }
            if (isset($clauseSess["string"]["smonth"])) { $smonth = $clauseSess["string"]["smonth"]; } else { $smonth = "01"; }
            if (isset($clauseSess["string"]["syear"])) { $syear = $clauseSess["string"]["syear"]; } else { $syear = "2000"; }
            if (isset($clauseSess["string"]["fday"])) { $fday = $clauseSess["string"]["fday"]; } else { $fday = date("d"); }
            if (isset($clauseSess["string"]["fmonth"])) { $fmonth = $clauseSess["string"]["fmonth"]; } else { $fmonth = date("m"); }
            if (isset($clauseSess["string"]["fyear"])) { $fyear = $clauseSess["string"]["fyear"]; } else { $fyear = date("Y"); }
            
            $this->view->objects_list(array("criterion" => $criterion, "sday" => $sday, "smonth" => $smonth, "syear" => $syear, "fday" => $fday, "fmonth" => $fmonth, "fyear" => $fyear));
    
    
            if (isset($clauseSess["string"]["submit"])) {
                
                $this->view->setMainContent("<p style='margin-bottom: 20px'><b>Последняя выборка</b></p>");
                
                $this->object->links = "/list";

                $data = $this->object->getObjectsByClause($clauseSess["string"]);
                
                $res_tree = null;

                foreach($data as $part) {
                    if ($obj = $this->object->getShortObject($part["id"])) {
                        
                        $template = "[" . $obj[0]["tname"] . "]";
                        $sub = $obj[0]["type_id"];

                        $viewObj = $this->view->render("objects_obj", (array("obj" => $obj, "ui" => $this->registry["ui"])));

                        $flag[1] = FALSE;
                        
                        foreach($this->tree[$obj[0]["tid"]] as $batch) {
                            if ($obj[0]["type_pid"] == $batch["pid"]) {
                                $count = 0;
                                if (isset($res_tree[$template][$sub])) {
                                    $count = count($res_tree[$template][$sub]);
                                }
                                
                                $flag[2] = FALSE;
                                if (isset($res_tree[$template][$sub])) {
                                    for($i=0; $i < count($res_tree[$template][$sub]); $i++) {
                                        if (($res_tree[$template][$sub][$i] == null) or ($res_tree[$template][$sub][$i] == $viewObj)) {
                                            $res_tree[$template][$sub][$i] = $viewObj;
                                            
                                            $flag[2] = TRUE;
                                        }
                                    }
                                }

                                if (!$flag[2]) {
                                    $res_tree[$template][$sub][$count] = $viewObj;
                                }

                                $flag[1] = TRUE;
                            }
                        } 
                        
                        if (!$flag[1]) {
                            $count = 0;
                            if (isset($res_tree[$template][0])) {
                                $count = count($res_tree[$template][0]);
                            }
                            
                            $res_tree[$template][0][$count] = $viewObj;
                        }
                    } else {
                        if (isset($part["tname"])) {
                            $template = "[" . $part["tname"] . "]";
                            $sub = $part["type_id"];
                            
                            if (!isset($res_tree[$template][$sub][0])) {
                                $res_tree[$template][$sub][0] = null;
                            }
                        }
                    }
                }

                $this->print_array($res_tree);
                
                $this->view->objects_find(array("list" => $this->find, "tree" => $this->tree, "templates" => $this->templates, "numT" => count($this->templates)));
            }
        }

        $this->view->showPage();
	}
}
?>
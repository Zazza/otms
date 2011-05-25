<?php
class Controller_Stat extends Controller_Index {
	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            $this->view->setLeftContent($this->view->render("left_stat", array("ui" => $this->registry["ui"])));
            
            $stat = new Model_Stat($this->registry);
            
            
            if (isset($_GET["clear"])) {
                unset($_SESSION["stat"]);
            }

            $statSess = & $_SESSION["stat"];
            
            if (isset($_POST["submit"])) {

                $_POST["sday"] = htmlspecialchars($_POST["sday"]);
                $_POST["smonth"] = htmlspecialchars($_POST["smonth"]);
                $_POST["syear"] = htmlspecialchars($_POST["syear"]);
                $_POST["fday"] = htmlspecialchars($_POST["fday"]);
                $_POST["fmonth"] = htmlspecialchars($_POST["fmonth"]);
                $_POST["fyear"] = htmlspecialchars($_POST["fyear"]);

                $statSess = $_POST;
            } else {
                if (!isset($statSess)) {
                    $statSess = array();
                }
            }
            
            if (!isset($statSess["sday"])) { $statSess["sday"] = "01"; }
            if (!isset($statSess["smonth"])) { $statSess["smonth"] = "01"; }
            if (!isset($statSess["syear"])) { $statSess["syear"] = "2010"; }
            if (!isset($statSess["fday"])) { $statSess["fday"] = date("d"); }
            if (!isset($statSess["fmonth"])) { $statSess["fmonth"] = date("m"); }
            if (!isset($statSess["fyear"])) { $statSess["fyear"] = date("Y"); }

            $this->view->stat_date(array("args0" => $args[0], "date" => $statSess));

            if ( ($args[0] == "groups") or (!isset($args[0])) ) {
                
                if (isset($args[1])) {
                    $stat->links = "/groups/" . $args[1];
                }
                
                if (isset($args[2])) {
        			if ( ($args[2] == "page") and (isset($args[3])) ) {
        				if (!$stat->setPage($args[3])) {
        					$this->__call("stat", "groups");
        				}
        			}
        		}
                
                if (isset($args[1])) {
                    $data = $stat->getGroupsStatFromGroups($statSess, $args[1]);
                    
                    if (!isset($args[2]) or ($args[2] == "page"))  {
                    
                        foreach($data as $part) {
                        
                            if ($data = $this->tt->getTask($part["id"])) {
                                $numComments = $this->tt->getNumComments($part["id"]);
                                
                                $author = $this->user->getUserInfo($data[0]["who"]);
                                
                                foreach($data as $val) {
                                    $ruser[] = $this->user->getUserInfo($val["uid"]);
                                }
                                
                                $object = new Model_Object($this->registry);
                                
                                $obj = $object->getShortObject($data[0]["oid"]);
                                
                                $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "notObj" => true, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
                                
                                unset($ruser);
                            }
                        }
                    
                        //Отобразим пейджер
            			if (count($stat->pager) != 0) {
            				$this->view->pager(array("pages" => $stat->pager));
            			}
                        
                    }
                } else {
                    $data = $stat->getGroupsStat($statSess);
                    
                    $this->view->stat_groups(array("data" => $data));
                }

            } elseif ($args[0] == "rusers") {
                
                if (isset($args[1])) {
                    $stat->links = "/groups/" . $args[1];
                }
                
                if (isset($args[2])) {
        			if ( ($args[2] == "page") and (isset($args[3])) ) {
        				if (!$stat->setPage($args[3])) {
        					$this->__call("stat", "rusers");
        				}
        			}
        		}
                
                if (isset($args[1])) {
                    $data = $stat->getRusersStatFromRid($statSess, $args[1]);
                    
                    if (!isset($args[2]) or ($args[2] == "page"))  {
                        
                        foreach($data as $part) {
                        
                            if ($data = $this->tt->getTask($part["id"])) {
                                $numComments = $this->tt->getNumComments($part["id"]);
                                
                                $author = $this->user->getUserInfo($data[0]["who"]);
                                
                                foreach($data as $val) {
                                    $ruser[] = $this->user->getUserInfo($val["uid"]);
                                }
                                
                                $object = new Model_Object($this->registry);
                                
                                $obj = $object->getShortObject($data[0]["oid"]);
                                
                                $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "notObj" => true, "obj" => $obj, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
                                
                                unset($ruser);
                            }
                        }
                    
                        //Отобразим пейджер
            			if (count($stat->pager) != 0) {
            				$this->view->pager(array("pages" => $stat->pager));
            			}
                        
                    }
                } else {
                    $data = $stat->getRusersStat($statSess);
                    
                    $this->view->stat_rusers(array("data" => $data));
                }
            }
        }
        
        $this->view->showPage();
    }
}
?>
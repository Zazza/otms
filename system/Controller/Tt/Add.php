<?php
class Controller_Tt_Add extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "add");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if (!$this->registry["ui"]["readonly"]) {
            
                $this->view->setTitle("Создать задачу");
                
                $this->view->setLeftContent($this->view->render("left_tt", array("ui" => $this->registry["ui"])));
                
                if (isset($_POST["submit"])) {
                    if (isset($_GET["oid"])) {
                        if ($_GET["oid"] != "") {
                            $oid = $_GET["oid"];
                        }
                    }
                    
                    if (isset($_POST["selObjHid"])) {
                        $oid = $_POST["selObjHid"];
                    }

                    if ($tid = $this->tt->addTask($oid, $_POST)) {
                        $this->tt->SpamUsers("Новая задача", $tid);
                        
                        $this->view->refresh(array("timer" => "1", "url" => "tt/" . $tid . "/"));
                    } else {
                        $this->view->setMainContent("<p style='margin: 30px 0 0 50px; color: red'>Заполните текст задачи и выберите объект!</span>");
                        
                        $this->view->refresh(array("timer" => "1", "url" => "tt/add/?oid=" . $oid . "&date=" . $_GET["date"]));
                    }
                } else {
        
                    $k=0;
                    $gdata = $this->user->getGroups();
                    $udata = $this->user->getUsersList();
                    for($i=0; $i<count($gdata); $i++) {
                        $data[$k]["id"] = $gdata[$i]["id"];
                        $data[$k]["type"] = "g";
                        $data[$k]["desc"] = $gdata[$i]["name"];
            
                        foreach($udata as $part) {
                            if ($part["gid"] == $gdata[$i]["id"]) {
                                
                                $k++;
                                
                                $data[$k]["id"] = $part["id"];
                                $data[$k]["type"] = "u";
                                $data[$k]["desc"] = $part["name"] . " " . $part["soname"];
                            }
                        }
                        
                        $k++;
                    }
                    
                    $data[$k]["type"] = "all";
                    $data[$k]["id"] = 0;
                    $data[$k]["desc"] = "все";
                    
                    $task = new Model_Task($this->registry);
                    if (isset($_GET["oid"])) {
                        $oid = $_GET["oid"];
                        $obj = $task->getShortObject($_GET["oid"]);
                    } else {
                        $oid = "";
                        $obj = "";
                    }
                    
                    if (isset($_GET["date"])) {
                        $now_date = date("Y", strtotime($_GET["date"])) . "-" . date("m", strtotime($_GET["date"])) . "-" . date("d", strtotime($_GET["date"]));
                        $now_time = "00:00:00";
                    } else {
                        $now_date = date("Y-m-d");
                        $now_time = date("H:i:s");
                    }

                    $this->view->tt_add(array("oid" => $oid, "rusers" => $data, "obj" => $obj, "now_date" => $now_date, "now_time" => $now_time));
                
                }
            }
        }

        $this->view->showPage();
	}
}
?>
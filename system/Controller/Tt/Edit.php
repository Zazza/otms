<?php
class Controller_Tt_Edit extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "edit");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if (!$this->registry["ui"]["readonly"]) {
            
                $this->view->setTitle("Правка задачи");
                
                $this->view->setLeftContent($this->view->render("left_tt", array("ui" => $this->registry["ui"])));
               
                if (isset($_POST["submit"])) {
                    if ($tid = $this->tt->editTask($_POST)) {
                        $this->tt->SpamUsers("Изменения в задаче", $_POST["tid"]);
                        
                        $this->view->refresh(array("timer" => "1", "url" => "tt/" . $_POST["tid"] . "/"));
                    } else {
                        $this->view->setMainContent("<p style='margin: 30px 0 0 50px; color: red'>Заполните текст задачи и выберите объект!</span>");
                        
                        $this->view->refresh(array("timer" => "1", "url" => "tt/edit/" . $_POST["tid"] . "/"));
                    }
                } else {
        
                    $k=0;
                    $gdata = $this->user->getGroups();
                    $udata = $this->user->getUsersList();
                    for($i=0; $i<count($gdata); $i++) {
                        $rusers[$k]["id"] = $gdata[$i]["id"];
                        $rusers[$k]["type"] = "g";
                        $rusers[$k]["desc"] = $gdata[$i]["name"];
            
                        foreach($udata as $part) {
                            if ($part["gid"] == $gdata[$i]["id"]) {
                                
                                $k++;
                                
                                $rusers[$k]["id"] = $part["id"];
                                $rusers[$k]["type"] = "u";
                                $rusers[$k]["desc"] = $part["name"] . " " . $part["soname"];
                            }
                        }
                        
                        $k++;
                    }
                    
                    $rusers[$k]["type"] = "all";
                    $rusers[$k]["id"] = 0;
                    $rusers[$k]["desc"] = "все";
                    
                    $data = $this->tt->getTask($args[1]);
                    
                    $task = new Model_Task($this->registry);
                    $obj = $task->getShortObject($data[0]["oid"]);
                    
                    $issRusers = array(); $k = 0;
                    foreach($data as $part) {
                        
                        if (($part["uid"]) != null) {                
                            $row = $this->user->getUserInfo($part["uid"]);
                            
                            $k++;
            
                            $issRusers[$k]["desc"] = '<p><span style="font-size: 11px; margin-right: 10px;" id="udesc[' . $row["uid"] . ']">' . $row["name"] . ' ' . $row["soname"] . '</span>';
                            $issRusers[$k]["desc"] .= '<input id="uhid[' . $row["uid"] . ']" type="hidden" name="ruser[]" value="' . $row["uid"] . '" /></p>';
                        }
                    }

                    $this->view->tt_edit(array("data" => $data, "rusers" => $rusers, "obj" => $obj, "issRusers" => $issRusers));
                
                }
            }
        }

        $this->view->showPage();
	}
}
?>
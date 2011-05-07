<?php
class Controller_Cron extends Controller_Index {
	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
	
	public function index($args) {
		$flag = FALSE;
		for($i=0; $i<count($this->registry["local"]); $i++) {
			if ($this->registry["local"][$i] == $this->registry["ip"]) {
				$flag = TRUE;
			}
		};

		if ($flag) {
           
    	    $task = new Model_Task($this->registry);
            
            $users = $this->user->getUsersList();
           
            foreach($users as $uid) {
                $nowdate = date("Ymd");
                
                $i = 0; $notify = array();
                
                if ($uid["notify"]) {
                    if (date("Ymd", strtotime($uid["last_notify"])) < date("Ymd")) {
                        if ($uid["time_notify"] < date("H:i:s")) {
                    
                            $tasks = $this->tt->getTasksDate($uid["id"], $nowdate);
                            
                            foreach($tasks as $part) {
                                if ($data = $this->tt->getTask($part["id"])) {
                                    $notify[$i]["id"] = $data[0]["id"];
                                    $notify[$i]["text"] = $data[0]["text"];
                                    
                                    $nobj = "";
                                    $obj = $task->getShortObject($data[0]["oid"]);
                                    foreach($obj as $val) {
                                        if ($val["main"]) {
                                            $nobj .= "<b>" . $val["field"] . ":</b> " . $val["val"] . " ";
                                        }
                                    }
                                    
                                    $notify[$i]["obj"] = $nobj;
    
                                    $notify[$i]["email"] = $uid["email"];
    
                                    $i++;
                                    
                                    unset($obj); unset($author); unset($ruser);
                                }
                            }
                            
                            if (count($notify) > 0) {
                                $this->helpers->sendNotify($notify, $i);
                            }
                            
                            $this->user->setNotifyTime($uid["id"]);
                        }
                    }
                }
            }
		} else {
			$this->__call("cron", $args);
			$this->view->showPage();
		};
    }
}
?>
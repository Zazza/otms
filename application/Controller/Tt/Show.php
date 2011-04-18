<?php
class Controller_Tt_Show extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "tt", "show");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
        
            $this->view->setTitle("Просмотр задачи");
            
            $this->view->setLeftContent($this->view->render("left_tt", array("ui" => $this->registry["ui"])));
            
            $author = array(); $ruser = array();
            
            if (isset($args[0])) {
                if ($data = $this->tt->getTask($args[0])) {
                    if (count($data) > 0) {
                        $numComments = $this->tt->getNumComments($args[0]);
                        
                        $author = $this->user->getUserInfo($data[0]["who"]);
                        
                        foreach($data as $part) {
                            $ruser[] = $this->user->getUserInfo($part["uid"]);
                        }
                        
                        $group = null;
                        if ($data[0]["gid"] != "0") {
                            $group = $this->tt->getGroupName($data[0]["gid"]);
                        }
                    
                        $task = new Model_Task($this->registry);
                        
                        if ($obj = $task->getShortObject($data[0]["oid"])) {
                            
                            $numTroubles = $task->getNumTroubles($data[0]["oid"]);
                            $advInfo = $task->getAdvancedInfo($data[0]["oid"]);
                            $numAdvInfo = $task->getNumAdvancedInfo($data[0]["oid"]);
                            $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles, "group" => $group));
                        } else {
                            $this->view->setMainContent("<p>Объект не найден</p>");
                        }
        
                        $this->view->tt_task(array("ui" => $this->registry["ui"], "data" => $data, "author" => $author, "ruser" => $ruser, "numComments" => $numComments, "uid" => $this->registry["ui"]["id"]));
                        
                        $comments = $this->tt->getComments($args[0]);
                        if (count($comments) > 0) {
                            $this->view->setMainContent("<h3>Комментарии:</h3>");
                        }
                        foreach ($comments as $part) {
                            $this->view->tt_comment(array("comment" => $part));
                        }
                    } else {
                        $this->view->setMainContent("<p>Задача не найдена</p>");
                    }            
                } else {
                    $this->view->setMainContent("<p>Задача не найдена</p>");
                }            
            }
        }
        
        $this->view->showPage();
	}
}
?>
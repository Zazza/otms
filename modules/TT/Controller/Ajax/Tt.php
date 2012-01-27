<?php
class Controller_Ajax_Tt extends Modules_Ajax {
	public function setCalTask($params) {
		$caltask = $params["caltask"];
		 
		$cal = & $_SESSION["cal"];
		 
		$cal["type"] = $caltask;
	}
	
	public function getMonth($params) {
		$month = htmlspecialchars($params["month"]);
		$year = htmlspecialchars($params["year"]);
	
		$data = $this->registry["tt"]->getMonthTasks($year, $month);
		foreach($data as $key=>$value) {
			if ($value["close"]["num"] > 0) {
				$close = '<span style=" margin-right: 5px"><img border="0" style="vertical-align: middle" alt="" src="' . $this->registry["uri"] . 'img/flag.png"><b>' . $value["close"]["num"] . '</b></span>';
			} else {
				$close = '';
			}
			if ($value["time"]["num"] > 0) {
				$time = '<span style=" margin-right: 5px"><img border="0" style="vertical-align: middle" alt="" src="' . $this->registry["uri"] . 'img/alarm-clock.png"><b>' . $value["time"]["num"] . '</b></span>';
			} else {
				$time = '';
			}
			if ($value["iter"]["num"] > 0) {
				$iter = '<span style="margin-right: 5px"><img border="0" alt="" src="' . $this->registry["uri"] . 'img/calendar-blue.png" style="position: relative; top: 3px"><b>' . $value["iter"]["num"] . '</b></span>';
			} else {
				$iter = '';
			}
			if ($value["noiter"]["num"] > 0) {
				$noiter = '<span style="margin-right: 5px"><img border="0" style="vertical-align: middle" alt="" src="' . $this->registry["uri"] . 'img/clock.png"><b>' . $value["noiter"]["num"] . '</b></span>';
			} else {
				$noiter = '';
			}
	
			$row[$key] = $close . $iter . $time . $noiter;
		}
	
		$row["first"] = date("N", mktime(0, 0, 0, $month, 1, $year));
		$row["num"] = date("t", mktime(0, 0, 0, $month, 1, $year));
	
		echo json_encode($row);
	}
    
    public function delGroup($params) {
        $gid = $params["gid"];
        
        $this->registry["tt"]->delGroup($gid);
    }
    
    public function delTemplate($params) {
        $id = $params["id"];
        
        $tpl = new Model_Template();
        $tpl->delTemplate($id);
    }

    public function getInfo($params) {
        $id = $params["id"];
        
        $object = new Model_Object();
        $data = $object->getObject($id);

        echo $this->view->render("objectInfo", array("data" => $data));
    }
    
    public function addAdvancedNote($params) {
    	$title = $params["title"];
    	$text = $params["text"];
    	$tags = htmlspecialchars($params["tags"]);
    
    	$advinfo = new Model_Ai();
    
    	$oaid = $advinfo->addAdvanced("0", $title, $text);
    
    	$arr = explode(",", $tags);
    	$arr = array_unique($arr);
    	foreach($arr as $part) {
    		$tag = trim($part);
    		if ($tag != "") {
    			$advinfo->addTags($oaid, $tag);
    		}
    	}
    }

    public function addAdvanced($params) {
        $id = $params["id"];
        $title = $params["title"];
        $text = $params["text"];
        $tags = htmlspecialchars($params["tags"]);
        
        $advinfo = new Model_Ai();

        $oaid = $advinfo->addAdvanced($id, $title, $text);
        
        $arr = explode(",", $tags);
		$arr = array_unique($arr);
        foreach($arr as $part) {
            $tag = trim($part);
            if ($tag != "") {
                $advinfo->addTags($oaid, $tag);
            }
        }
    }
    
    public function delAdv($params) {
        $id = $params["id"];
        
        $advinfo = new Model_Ai();
        
        $advinfo->delAdvanced($id);
    }
    
    public function editAdvanced($params) {
        $oid = $params["oid"];
        $title = $params["title"];
        $text = $params["text"];
        $tags = htmlspecialchars($params["tags"]); echo $oid . " " . $title . " " . $text . " " . $tags;
        
        $advinfo = new Model_Ai();
        
        $advinfo->editAdvanced($oid, $title, $text);
        $advinfo->changeTags($oid, $tags);
    }
    
    public function findObj($params) {
        $tfind = $params["find"];
        
        $find = new Model_Find();
        $object = new Model_Object();
        
        $findSess = & $_SESSION["find"];
        
        if (isset($tfind)) {
            $findSess["string"] = $tfind;
        } else {
            if (!isset($findSess["string"])) {
                $findSess["string"] = "";
            }
        }
        
        if (isset($findSess["string"])) {
            
            $text = substr($findSess["string"], 0, 64);
			$text = explode(" ", $text);

            $tfind = $find->findObjects($text);
            
            if (count($tfind) == 0) {
                echo "<p style='color: #777; margin-left: 20px'>Ничего не найдено</p>";
            }
   
            foreach ($tfind as $part) {
                echo '<div style="margin-bottom: 20px">';
                
                $obj = $object->getShortObject($part["id"]);
                foreach($obj as $val) {
                    echo "<p><b>" . $val["field"] . ":</b> " . $val["val"] . "</p>";

                    $row[$val["field"]] = $val["val"];
                }
                
                $row["id"] = $val["id"];
                
                $data = json_encode($row);
                
                echo "<p><img src='" . $this->registry['uri'] . "img/enter.png' alt='' style='vertical-align: middle; margin-right: 5px'><a style='cursor: pointer' onclick='selObj(" . $data . ")'>выбрать</a></p>";
                echo '</div>';
            }
        }
    }
    
    public function addComment($params) {
    	$this->registry["tt"]->uid = $this->registry["ui"]["id"];
    	
        $tid = $params["tid"];
        $text = $params["text"];
        $status = $params["status"];
        $post["attaches"] = json_decode($params["json"] , true);

        $this->registry["tt"]->addComment($tid, $text, $status, $post, false);
        
        $this->registry["tt"]->spamUsers("Новый комментарий", $tid);
    }
    
    public function closeTask($params) {
        $tid = $params["tid"];
        
        $this->registry["tt"]->closeTask($tid);
        
        $this->registry["tt"]->spamUsers("Задача закрыта", $tid);
    }

    public function delDraft($params) {
    	$did = $params["did"];
    	
    	$this->registry["tt"]->delDraft($did);
    }
    
    public function setSortMyTt($params) {
    	$sort = $params["sort"];
    	$id = $params["id"];
    	
    	$sortmytt = & $_SESSION["sortmytt"];
    	$sortmytt["sort"] = $sort;
    	$sortmytt["id"] = $id;
    }
    
	public function getListUsers() {
		$tree = $this->registry["module_users"]->users_tree();
		echo $this->view->render("tt_utree", array("list" => $tree));
    }
    
    public function getDelegateUsers() {
    	$tree = $this->registry["module_users"]->onlyUsers_tree();
    	echo $this->view->render("tt_onlyUtree", array("list" => $tree));
    }
}
?>
<?php
class Controller_Ajax_Objects extends Modules_Ajax {
    
    public function delGroup($params) {
        $gid = $params["gid"];
        
        $this->registry["tt"]->delGroup($gid);
    }
    
    public function delTemplate($params) {
        $id = $params["id"];
        
        $tpl = new Model_Template();
        $tpl->delTemplate($id);
    }
    
    public function getTemplateFields($params) {
        $id = $params["id"];
        
        $tpl = new Model_Template();
        $fields = $tpl->getTypeTemplate($id);

        echo $this->view->render("objects_fields", array("fields" => $fields));
    }
    
    public function getInfo($params) {
        $id = $params["id"];
        
        if (isset($this->registry["module_mail"])) {
        	$mail = true;
        } else {
        	$mail = false;
        }
        
        $object = new Model_Object();
        $data = $object->getObject($id);

        echo $this->view->render("objectInfo", array("data" => $data, "mail" => $mail));
    }
    
    public function addAdvancedNote($params) {
    	$title = $params["title"];
    	$text = $params["text"];
    	$tags = htmlspecialchars($params["tags"]);
    
    	$advinfo = $this->registry["module_kb"];
    
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
        
        $advinfo = $this->registry["module_kb"];

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
        
        $advinfo = $this->registry["module_kb"];
        
        $advinfo->delAdvanced($id);
    }
    
    public function editAdvanced($params) {
        $oid = $params["oid"];
        $title = $params["title"];
        $text = $params["text"];
        $tags = htmlspecialchars($params["tags"]); echo $oid . " " . $title . " " . $text . " " . $tags;
        
        $advinfo = $this->registry["module_kb"];
        
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
        
        $this->registry["tt"]->SpamUsers("Новый комментарий", $tid);
    }
    
    public function closeTask($params) {
        $tid = $params["tid"];
        
        $this->registry["tt"]->closeTask($tid);
        
        $this->registry["tt"]->SpamUsers("Задача закрыта", $tid);
    }
    
    public function addTree($params) {
        $id = $params["id"];
        $name = htmlspecialchars($params["name"]);
        
        $template = new Model_Template();
        $template->addTree($id, $name);
    }

    public function getTree($params) {
        $id = $params["id"];
        
        $template = new Model_Template();
        
        $tree = $template->getTree($id);
        
        echo $this->view->render("objects_tree", array("tree" => $tree));
    }
    
    public function delCat($params) {
        $id = $params["id"];
        
        $template = new Model_Template();
        
        $template->delCat($id);
    }
    
    public function editCat($params) {
        $id = $params["id"];
        $name = htmlspecialchars($params["name"]);
        
        $template = new Model_Template();
        
        $template->editCat($id, $name);
    }
    
    public function getCatName($params) {
        $id = $params["id"];
        
        $template = new Model_Template();
        
        $cat = $template->getCatName($id);
         
        echo $cat["name"];
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
    
    public function move_objs($params) {
    	$objs = json_decode($params["json"], true);
    	$sub = $params["sub"];
    
    	$templates = new Model_Template($this->registry);
    	$objects = new Model_Object($this->registry);
    
    	if (count($objs) > 0) {
    		foreach($objs as $key=>$val) {
    			$oid = mb_substr($key, 4, mb_strlen($key)-5);
    			$objects->moveObj($oid, $sub);
    		}
    	}
    }
    
}
?>
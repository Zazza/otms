<?php
class Controller_Ajax_Tt extends Controller_Ajax_Index {

	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
    
    public function delGroup($params) {
        $id = $params["id"];
        
        $this->tt->delGroup($id);
    }
    
    public function delTemplate($params) {
        $id = $params["id"];
        
        $task = new Model_Task($this->registry);
        $task->delTemplate($id);
    }
    
    public function getTemplateFields($params) {
        $id = $params["id"];
        
        $task = new Model_Task($this->registry);
        $fields = $task->getTypeTemplate($id);

        echo $this->view->render("objects_fields", array("fields" => $fields));
    }
    
    public function getInfo($params) {
        $id = $params["id"];
        
        $task = new Model_Task($this->registry);
        $data = $task->getObject($id);

        echo $this->view->render("objectInfo", array("data" => $data));
    }
    
    public function addAdvanced($params) {
        $id = $params["id"];
        $text = $params["text"];
        $tags = htmlspecialchars($params["tags"]);
        
        $task = new Model_Task($this->registry);

        $oaid = $task->addAdvanced($id, $text);
        
        $arr = explode(",", $tags);
		$arr = array_unique($arr);
        foreach($arr as $part) {
            $tag = trim($part);
            if ($tag != "") {
                $task->addTags($oaid, $tag);
            }
        }
    }
    
    public function delAdv($params) {
        $id = $params["id"];
        
        $task = new Model_Task($this->registry);
        
        $task->delAdvanced($id);
    }
    
    public function getAdvanced($params) {
        $id = $params["id"];
        
        $task = new Model_Task($this->registry);
        
        $arr = $task->getTags($id);
        $ai["tags"] = implode(", ", $arr);
        $ai["adv"] = $task->getAdvanced($id);

        echo json_encode($ai);
    }
    
    public function editAdvanced($params) {
        $id = $params["id"];
        $text = $params["text"];
        $tags = htmlspecialchars($params["tags"]);
        
        $task = new Model_Task($this->registry);
        
        $task->editAdvanced($id, $text);
        $task->changeTags($id, $tags);
    }
    
    public function findObj($params) {
        $tfind = $params["find"];
        
        $find = new Model_Find($this->registry);
        $task = new Model_Task($this->registry);
        
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
                
                $obj = $task->getShortObject($part["id"]);
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
        $tid = $params["tid"];
        $text = $params["text"];
        
        $this->tt->addComment($tid, $text);
        
        $this->tt->SpamUsers("Новый комментарий", $tid);
    }
    
    public function closeTask($params) {
        $tid = $params["tid"];
        $gid = $params["gid"];
        
        $this->tt->closeTask($tid, $gid);
        
        $this->tt->SpamUsers("Задача закрыта", $tid);
    }
    
    public function addTree($params) {
        $id = $params["id"];
        $pid = $params["pid"];
        $name = htmlspecialchars($params["name"]);
        
        $template = new Model_Template($this->registry);
        $template->addTree($id, $pid, $name);
        
        $tree = $template->getTree($id);
        
        echo $this->view->render("settings_tree", array("tree" => $tree));
    }

    public function getTree($params) {
        $id = $params["id"];
        
        $template = new Model_Template($this->registry);
        
        $tree = $template->getTree($id);
        
        echo $this->view->render("settings_tree", array("tree" => $tree));
    }
    
    public function delCat($params) {
        $id = $params["id"];
        
        $template = new Model_Template($this->registry);
        
        $template->delCat($id);
    }
    
    public function editCat($params) {
        $id = $params["id"];
        $name = htmlspecialchars($params["name"]);
        
        $template = new Model_Template($this->registry);
        
        $template->editCat($id, $name);
    }
    
    public function getCatName($params) {
        $id = $params["id"];
        
        $template = new Model_Template($this->registry);
        
        echo $template->getCatName($id);
    }
}
?>
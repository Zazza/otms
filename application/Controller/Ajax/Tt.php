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
        $fields = $task->getTemplate($id);

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
        
        $task = new Model_Task($this->registry);

        $task->addAdvanced($id, $text);
    }
    
    public function delAdv($params) {
        $id = $params["id"];
        
        $task = new Model_Task($this->registry);
        
        $task->delAdvanced($id);
    }
    
    public function getAdvanced($params) {
        $id = $params["id"];
        
        $task = new Model_Task($this->registry);
        
        echo $task->getAdvanced($id);
    }
    
    public function editAdvanced($params) {
        $id = $params["id"];
        $text = $params["text"];
        
        $task = new Model_Task($this->registry);
        
        $task->editAdvanced($id, $text);
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
                
                echo "<p><img src='/img/enter.png' alt='' style='vertical-align: middle; margin-right: 5px'><a style='cursor: pointer' onclick='selObj(" . $data . ")'>выбрать</a></p>";
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
}
?>
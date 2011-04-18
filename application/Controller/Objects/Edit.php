<?php
class Controller_Objects_Edit extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "objects", "edit");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if (!$this->registry["ui"]["readonly"]) {
            
                $this->view->setTitle("Правка объекта");
                
                $this->view->setLeftContent($this->view->render("left_objects", array("ui" => $this->registry["ui"])));
                
                $task = new Model_Task($this->registry);
                
                if (isset($_POST["submit"])) {
                    
                    $task->editObject($_POST);
                    
                    $this->view->refresh(array("timer" => "1", "url" => "/objects/edit/" . $_POST["tid"] . "/"));
                    
                } else {
                    
                    if (isset($args[1])) {
                        $data = $task->getObject($args[1]);
                        $this->view->objects_edit(array("vals" => $data));
                    }
                    
                }
            }
        }
        
        $this->view->showPage();
	}
}
?>
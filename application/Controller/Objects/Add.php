<?php
class Controller_Objects_Add extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "objects", "add");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if (!$this->registry["ui"]["readonly"]) {
            
                $this->view->setTitle("Добавить объект");
                
                $this->view->setLeftContent($this->view->render("left_objects", array("ui" => $this->registry["ui"])));
                
                $task = new Model_Task($this->registry);
                
                if (isset($_POST["submit"])) {
                    
                    $task->addObject($_POST);
                    
                    $this->view->refresh(array("timer" => "1", "url" => "/objects/add/"));
                    
                } else {
                
                    $templates = $task->getTemplates();
                    $this->view->objects_add(array("list" => $templates));
                }
            }
        }
        
        $this->view->showPage();
	}
}
?>
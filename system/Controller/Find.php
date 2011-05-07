<?php
class Controller_Find extends Controller_Index {
    protected $registry;
    
    protected $findSess = null;
    protected $numFind = null;
    
	public function __construct($registry) {
		$this->registry = $registry;
	}
    
    protected function begin($action, $args) {
        parent::__construct($this->registry, $action, $args);
        
        $find = new Model_Find($this->registry);
        
        $this->findSess = & $_SESSION["find"];
        
        if (isset($_POST["find"])) {
            $_POST["find"] = htmlspecialchars($_POST["find"]);
            $this->findSess["string"] = $_POST["find"];
        } else {
            if (!isset($this->findSess["string"])) {
                $this->findSess["string"] = "";
            }
        }
        
        $tfind = explode(" ", substr($this->findSess["string"], 0, 64));
        
        $this->numFind = $find->getNumFinds($tfind);
        
        $this->view->setLeftContent($this->view->render("left_find", array("num" => $this->numFind)));
    }
	
	public function index($args) {
        if ($this->registry["auth"]) {
            
            if (isset($args[0])) {
                if ($args[0] == "objects") {
                    
                    $controller = new Controller_Find_Objects($this->registry);
                    $controller->index($args);
    
                } elseif ($args[0] == "tasks") {
                    
                    $controller = new Controller_Find_Tasks($this->registry);
                    $controller->index($args);

                } elseif ($args[0] == "adv") {
                    
                    $controller = new Controller_Find_Adv($this->registry);
                    $controller->index($args);

                } else {
                    
                    $controller = new Controller_Find_Tasks($this->registry);
                    $controller->index($args);
                    
                }
            } else {

                $controller = new Controller_Find_Tasks($this->registry);
                $controller->index($args);

            }
            
        }
    }
}
?>
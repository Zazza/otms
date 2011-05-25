<?php
class Controller_Objects_Show extends Controller_Objects {
    
    public function __construct($registry) {
		parent::__construct($registry);
        
        $this->begin("objects", "show");
	}
	
	public function index($args) {            
        $this->view->setTitle("Объект");

        $object = new Model_Object($this->registry);
        $ai = new Model_Ai($this->registry);
        
        if ($obj = $object->getShortObject($args["0"])) {
            
            $numTroubles = $object->getNumTroubles($args["0"]);
            $advInfo = $ai->getAdvancedInfo($args["0"]);
            $numAdvInfo = $ai->getNumAdvancedInfo($args["0"]);
            $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
        } else {
            $this->view->setMainContent("<p>Объект не найден</p>");
        }
        
        $this->view->showPage();
	}
}
?>
<?php
class Controller_Objects_Show extends Controller_Objects {
	
	public function index() {            
        $this->view->setTitle("Объект");

        $object = new Model_Object();
        $ai = $this->registry["module_kb"];
        $forms = $ai->getForms();
        
        if ($obj = $object->getShortObject($this->args[0])) {
            
            $numTroubles = $object->getNumTroubles($this->args[0]);
            $advInfo = $ai->getAdvancedInfo($this->args[0]);
            $numAdvInfo = $ai->getNumAdvancedInfo($this->args[0]);
            $this->view->objectMain(array("ui" => $this->registry["ui"], "obj" => $obj, "advInfo" => $advInfo, "forms" => $forms, "numAdvInfo" => $numAdvInfo, "numTroubles" => $numTroubles));
        } else {
            $this->view->setMainContent("<p>Объект не найден</p>");
        }
        
        $this->view->showPage();
	}
}
?>
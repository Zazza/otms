<?php
class Controller_Ajax_Kb extends Modules_Ajax {
    
    public function getInfo($params) {
        $id = $params["id"];
        
        $ai = new Model_Ai();
        
        $part = $ai->getAdvanced($id);
        
        $json = false;
        
        if ($ainfo = json_decode($part["val"], true)) {
        	$json = true;
        	$part["val"] = null;
        	foreach($ainfo as $key=>$val) {
        		$part["val"] .= "<b>" . $key . "</b>: " . $val . "<br />";
        	}
        }
        
        echo $this->view->render("ai", array("ai" => $part, "json" => $json));
    }
    
    public function getFormFields($params) {
    	$id = $params["id"];
    	
    	$ai = new Model_Ai();
    	
    	$fields = $ai->getForm($id);
    	
    	echo $this->view->render("kb_formfields", array("fields" => $fields));
    }
    
    public function delForm($params) {
    	$id = $params["id"];
    	 
    	$ai = new Model_Ai();
    	 
    	$ai->delForm($id);
    }
}
?>
<?php
class Controller_Ajax_Kb extends Controller_Ajax_Index {

	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}
    
    public function delGroup($params) {
        $id = $params["id"];
        
        $this->kb->delGroup($id);
    }    
}
?>
<?php
class Objects_Public_Functions extends Modules_Functions {
	function renderObject($param) {
		if (isset($this->registry["module_mail"])) {
			$mail = true;
		} else {
			$mail = false;
		}
		
		return $this->view->render("objectMain", array("ui" => $param[0],
		"mail" => $mail,
		"obj" => $param[1],
		"advInfo" => $param[2],
		"numAdvInfo" => $param[3],
		"forms" => $param[4],
		"numTroubles" => $param[5],
		"group" => $param[6]));
	}
	
	function addObject($param) {
		$object = new Model_Object();
		$object->addObject($param[0], $param[1]);
	}
	
	function editObject($param) {
		$object = new Model_Object();
		$object->editObject($param[0], $param[1]);
	}
	
	function getFidFromFname($param) {
		$template = new Model_Template();
		return $template->getFidFromFname($param[0], $param[1]);
	}
	
	function getOidFromUniqId($param) {
		$object = new Model_Object();
		return $object->getOidFromUniqId($param[0], $param[1]);
	}
}
?>
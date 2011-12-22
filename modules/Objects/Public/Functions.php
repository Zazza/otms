<?php
class Objects_Public_Functions extends Modules_Functions {
	function renderObject($param) {
		if (isset($this->registry["module_users"])) {
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
}
?>
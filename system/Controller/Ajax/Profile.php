<?php
class Controller_Ajax_Profile extends Engine_Interface {
	function delAva($profile) {
		$ui = new Model_Ui();
		$ui->delAva();
	}
}
?>
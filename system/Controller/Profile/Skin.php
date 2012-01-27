<?php
class Controller_Profile_Skin extends Controller_Profile {

	public function index() {
		$this->view->setTitle("Скин");
		
		$ui = new Model_Ui();
		
		if (isset($_POST["submit"])) {
			$ui->setSkin($_POST["skin"]);
			
			$this->view->refresh(array("timer" => "1", "url" => "profile/skin/"));
		}
		
		if ($dh  = opendir($this->registry["rootPublic"] . "/" . $this->registry["path"]["skins"])) {
			while (false !== ($filename = readdir($dh))) {
				if ( ($filename != ".") and ($filename != "..") ) {
					$skins[] = $filename;
				}
			}
		}
		
		$this->view->skin(array("skins" => $skins));
	}
}	
?>
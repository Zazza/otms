<?php
class Controller_Ajax_Cmd extends Modules_Ajax {
	private $cmd;
	
	public function __construct($config) {
		parent::__construct($config);

		$this->cmd = $this->registry["cmd"];
	}

	public function addCmd($params) {
		$message = htmlspecialchars($params["message"]);

		$result = $this->cmd->set($message);
	
		$text = $this->cmd->get();

		echo "<span class='ps'>" . $this->registry["ui"]["login"] . "[" . date("H:i:s") . "]#</span> <span style='color: white'>" . $message . "</span><br />" .  $text;
	}
}
?>

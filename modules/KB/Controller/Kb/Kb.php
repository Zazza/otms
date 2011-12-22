<?php
class Controller_Kb_Kb extends Controller_Kb {

	public function index() {
		$advinfo = new Model_Ai();
		$tpl = new Model_Template();

		if (isset($this->args[0])) {
			if ($this->args[0] == "history") {
				$this->view->setTitle("История");
		
				$tasks = $this->registry["logs"]->getHistory("info", $this->args[1]);
		
				foreach($tasks as $task) {
					if ($task["param"][0]["key"]) {
						$this->view->history(array("obj" => $task));
					}
				}
			}
		} elseif (isset($_GET["tag"])) {
			$this->view->setTitle("Тег: " . htmlspecialchars($_GET["tag"]));
			
			$ai = $advinfo->getAIFromTag($_GET["tag"]);

			$this->view->setMainContent("<b>Тег:</b> " . htmlspecialchars($_GET["tag"]));
			
			$json = false;
			
			foreach($ai as $part) {
				if ($ainfo = json_decode($part["val"], true)) {
					$json = true;
					$part["val"] = null;
					foreach($ainfo as $key=>$val) {
						$part["val"] .= "<b>" . $key . "</b>: " . $val . "<br />";
					}
				}
				
				$this->view->ai(array("ai" => $part, "json" => $json));
			}
		} else {
			$this->view->setTitle("Теги");
			
			$templates = $tpl->getTemplates();
			$id = count($templates);
			$templates[$id]["id"] = 0;
			$templates[$id]["name"] = "Заметки";
			$list = $advinfo->getAi();
			
			for($i=0; $i<count($list); $i++) {
				if ($list[$i]["oid"] == "0") {
					$list[$i]["name"] = "Заметки";
				}
			}

			$sortlist = array();
			foreach($templates as $template) {
				foreach($list as $kb) {
					if ($kb["name"] == $template["name"]) {
						$sortlist[$template["name"]][] = $kb["tag"];
					}
				}
			}

			$this->print_array($sortlist);

			$this->view->kb_tree(array("list" => $this->tree));
		}

		 
		$this->view->showPage();
	}
}
?>
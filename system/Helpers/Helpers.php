<?php
class Helpers_Helpers extends Engine_Helper {
	private $text = false;
	
    function sendMail($email, $theme, $post, $comments = FALSE) {
    	$settings = new Model_Settings($this->registry);
    	
		if (isset($this->registry["module_users"])) {
	        $who = $this->registry["module_users"]->getUserInfo($post[0]["who"]);
	        $post[0]["who"] = $who["name"] . " " . $who["soname"];
	        
	        $post[0]["start"] = $settings->editDate($post[0]["start"]);
	        $post[0]["ending"] = $settings->editDate($post[0]["ending"]);
	        
	        for($i=0; $i < count($comments); $i++) {
	            $comments[$i]["timestamp"] = $settings->editDate($comments[$i]["timestamp"]);
	        }
	        
	        $text = $this->view->render("mail", array("post" => $post, "comments" => $comments));
	        
	        $_POST["subject"] = " <<< Задача " . $post[0]["id"] . ". " . $theme . " >>>";
	        $_POST["textfield"] = $text;
	        $_POST["to"] = $email;
	        
	        $this->phpmailer($_POST);
		}
    }
    
    function sendNotify($post, $num) {
        $text = $this->view->render("notify", array("sitename" => $this->registry["siteName"], "post" => $post));
        
        $_POST["subject"] = " <<< Задачи на день [" . $num . " задач(а,и)]>>>";
        $_POST["textfield"] = $text;
        $_POST["to"] = $post[0]["email"];

    	$this->phpmailer($_POST);
    }
    
    function sendTask($email, $subject, $post = null) {
    	$this->text = true;
    	
        $_POST["subject"] = json_encode($subject);
        $_POST["textfield"] = json_encode($post);
        $_POST["to"] = $email;
        $_POST["mail"] = $post["mail"];
        if (isset($_POST["mail_id"])) {
        	$_POST["mail_id"] = $post["mail_id"];
        }
        if (isset($post["attaches"])) {
        	$_POST["attaches"] = $post["attaches"];
        }

    	$this->phpmailer($_POST);
    }
    
    function phpmailer($_POST, $smtp = null, $fromName = null) {
        	$settings = new Model_Settings($this->registry);
        	if (isset($this->registry["module_mail"])) {
        		$mailClass = $this->registry["module_mail"];
        	}
        
        	if ($smtp == null) {
        		$smtp = $settings->getMailbox();
        	}
        	
        	if ($fromName == null) {
        		$fromName = $this->registry["mailSenderName"];
        	}
    	
			$mailer = new Phpmailer_Phpmailer();
			
			$err = array();
			
			$mailer->SMTPDebug = 0;
			
			$mailer->CharSet = "utf-8";

			$mailer->IsSMTP();
			$mailer->Host = $smtp["server"];
			$mailer->Port = $smtp["port"];
			
			if ($smtp["ssl"] == "ssl") {
				$mailer->SMTPSecure = "ssl";
			}
			
			if ( ($smtp["login"]) and ($smtp["password"]) ) {
				$mailer->SMTPAuth = true;
				$mailer->Username = $smtp["login"];
				$mailer->Password = $smtp["password"];
			} else {
				$mailer->SMTPAuth = false;
			}
			
			$mailer->From = $smtp["email"];
			$mailer->FromName = $fromName;
			
			if ($_POST["to"] == null) {
				$err[] = "Не заданы адресаты";
			} else {
				$to = explode(",", $_POST["to"]);
				for($i=0; $i<count($to); $i++) {
					$mailer->AddAddress($to[$i]);
				}
			}

			if (isset($_POST["attaches"])) {
				foreach($_POST["attaches"] as $part) {
					$filename = mb_substr($part, mb_strrpos($part, DIRECTORY_SEPARATOR) + 1, mb_strlen($part)-mb_strrpos($part, DIRECTORY_SEPARATOR));
					
					if ( (isset($_POST["mail"])) and ($_POST["mail"]) ) {
						$dir = $this->registry["path"]["attaches"];
						$md5 = $mailClass->getFile($_POST["mail_id"], $filename);
					} else {
						$dir = $this->registry["path"]["upload"];
						$md5 = $mailClass->getFileMD5($part);
					}

					$mailer->AddAttachment($this->registry["rootPublic"] . $dir . $md5, $filename);
				}
			}
			
			if (!$this->text) {
				$mailer->IsHTML(true);
				
				$mailer->Subject = $_POST["subject"];
				$mailer->Body = $_POST["textfield"];
				$mailer->AltBody = strip_tags($_POST["textfield"]);
			} else {
				$mailer->IsHTML(false);
				
				$mailer->Subject = base64_encode($_POST["subject"]);
				$mailer->Body = base64_encode($_POST["textfield"]);
			}			
			
			if ($_POST["textfield"] == null) { $err[] = "Пустое письмо"; };
						
			if (count($err) == 0) {

				if(!$mailer->Send()) {
					return $mailer->ErrorInfo;
				} else {
					return false;
				}
			} else {
				return $err;
			}
    }
}
?>
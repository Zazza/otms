<?php
class Helpers_Helpers extends Helpers_Index {
    public function sendMail($email, $theme, $post, $comments = FALSE) {
        $user = new Model_User($this->registry);
        
        foreach($post as $val) {
            $rusers[] = $user->getUserInfo($val["uid"]);
        }
        
        $who = $user->getUserInfo($post[0]["who"]);
        $post[0]["who"] = $who["name"] . " " . $who["soname"];
        
        $post[0]["ending"] = $user->editDate($post[0]["ending"]);
        
        for($i=0; $i < count($comments); $i++) {
            $comments[$i]["timestamp"] = $user->editDate($comments[$i]["timestamp"]);
        }
        
        $text = $this->view->render("mail", array("sitename" => $this->registry["siteName"], "post" => $post, "comments" => $comments, "rusers" => $rusers));
        
    	$subject = $theme . " <<< Задача №" . $post[0]["id"] . " >>>";

    	$headers = 'From: ' . $this->registry["mailSender"] . "\r\n";
    	$headers .= 'Content-Type: text/html; charset="utf-8"' . "\r\n";
        $headers .= 'Reply-To: ' . $this->registry["mailSender"] . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

    	mail($email, $subject, $text, $headers);
    }
    
    public function sendNotify($post, $num) {
        $text = $this->view->render("notify", array("sitename" => $this->registry["siteName"], "post" => $post));

    	$subject = " <<< Задачи на день [" . $num . "]>>>";

    	$headers = 'From: ' . $this->registry["mailSender"] . "\r\n";
    	$headers .= 'Content-Type: text/html; charset="utf-8"' . "\r\n";
        $headers .= 'Reply-To: ' . $this->registry["mailSender"] . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

    	mail($post[0]["email"], $subject, $text, $headers);
    }
}
?>
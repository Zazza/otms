<?php
/*
*
* "api" => true (параметр для идентификации POST запроса к HelpDesk)
*
* sid - sender id (ID отправителя задачи)
*
* "addressee" => "user"
* "addressee" => "group"
* "addressee" => "all"
* (кому адресована задача, одному пользователю HelpDesk, группе пользователей или всем)
*
* aname - addressee id(user or group) (имя, кому адресована задача)
*
* text - message (текст задачи)
*
*/

$url = "http://www.example.com";
$curl = curl_init($url);

$sid = 1;
$addressee = "";
$aname = "";
$text = "";

$xml = array("api" => true, "sid" => $sid, "addressee" => $addressee, "aname" => $aname, "text" => $text);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($curl);
curl_close($curl);
?>
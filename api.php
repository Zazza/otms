<?php
/*
*
* "method" => add (метод для создания задачи)
*
* "oid" => ID объекта
*
* "recipient" => "user"
* "recipient" => "group"
* "recipient" => "all"
* (кому адресована задача, одному пользователю HelpDesk, группе пользователей или всем)
*
* "rid" - recipient(user or group) (логин или имя группы, кому адресована задача, пользователя или группы)
*
* text - message (текст задачи)
*
*/

$url = "http://tushkan.homelinux.com/otms/";
$curl = curl_init($url);

$param["method"] = "add";
$param["oid"] = "";
$param["login"] = "";
$param["pass"] = "";
$param["recipient"] = "";
$param["rid"] = ""; 
$param["text"] = "";

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($curl); echo $result;
curl_close($curl);
?>
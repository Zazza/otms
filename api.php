<?php
/*
*
* $url = "http://url/otms/" - url проекта, куда обращается API
* $param["method"] = "add" (метод для создания задачи)
* $param["oid"] - ID объекта
* $param["login"] - логин пользователя, из-под которого отправляем задачу
* $param["pass"]- пароль пользователя, из-под которого отправляем задачу
* $param["recipient"]  - "user", "group", "all"
* (кому адресована задача, одному пользователю HelpDesk, группе пользователей или всем)
* $param["rid"]  - recipient(user or group) (логин или имя группы, кому адресована задача, пользователя или группы)
* $param["text"] - message (текст задачи)
*
*/

$url = "http://url/otms/";
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

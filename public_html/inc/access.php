<?php
if(!defined('_TUBEWEB_')) exit; // ���� ������ ���� �Ұ�

if($_SERVER["QUERY_STRING"])
	$url = $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
else
	$url = $_SERVER["PHP_SELF"];

$url = urlencode($url);

if(!$member['id']) {
	goto_url(TW_BBS_URL."/login.php?url=$url");
}
?>
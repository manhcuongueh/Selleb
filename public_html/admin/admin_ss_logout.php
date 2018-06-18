<?php
include_once("_common.php");

session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함

goto_url(TW_ADMIN_URL."/admin_ss_login.php?mb_id=".$_GET['mb_id']);
?>
<?php
include_once("./_common.php");

if(!$member['id'])
	alert_close("회원 전용 서비스입니다.");

$gw_head_title = '배송지 찾기';
include_once(TW_PATH."/head.sub.php");

$sql_common = " from shop_order ";
$sql_search = " where mb_no = '$mb_no' ";
$sql_order  = " group by odrkey order by orderdate_s desc ";

$sql = " select COUNT(DISTINCT odrkey) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select * $sql_common $sql_search $sql_order ";
$result = sql_query($sql);

include_once($theme_path.'/orderaddress.skin.php');

include_once(TW_PATH."/tail.sub.php");
?>
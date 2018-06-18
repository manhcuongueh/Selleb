<?php
include_once("./_common.php");

if(!$member['id'])
	goto_url(TW_BBS_URL."/login.php?url={$nowurl}");

$gw_head_title = '적립금조회';
include_once("./_head.php");

$sql = "select SUM(income) as incom,
			   SUM(outcome) as outcom 
		  from shop_point 
		 where mb_no = '$mb_no' "; 
$sum = sql_fetch($sql);
$incom  = $sum['incom'];
$outcom = $sum['outcom'];

$sql_common = " from shop_point ";
$sql_search = " where mb_no = '$mb_no' ";
$sql_order  = " order by wdate desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once($theme_path.'/point.skin.php');

include_once("./_tail.php");
?>
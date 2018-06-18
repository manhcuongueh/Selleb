<?php
include_once("./_common.php");

if(!$is_member)
	goto_url("./login.php?url=".urlencode("$tb[bbs_root]/wish.php"));

$tb['title'] = "찜한상품";
include_once("./_head.php");

$sql_common = " from shop_wish a left join shop_goods b ON ( a.gs_id = b.index_no ) ";
$sql_search = " where a.mb_id = '{$member['id']}' ";
$sql_order  = " order by a.wi_id desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 5;
$total_page  = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once($theme_path.'/wish.skin.php');
include_once("./_tail.php");
?>
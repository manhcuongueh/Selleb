<?php
include_once("./_common.php");

$tb['title'] = "구매후기";
include_once("./_head.php");

$sql_common = " from shop_goods_review ";
$sql_search = " where (left(gs_se_id,3) = 'AP-' or gs_se_id = 'admin' or gs_se_id = '$pt_id') ";
if($default['de_review_wr_use']) { 
	$sql_search .= " and pt_id = '$pt_id' ";
}
$sql_order  = " order by wdate desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = (int)$row['cnt'];

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once($theme_path."/review.skin.php");
include_once("./_tail.php");
?>

<?php
include_once("./_common.php");

$type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $_REQUEST['type']);
if($type == 1)      $gw_head_title = '쇼핑특가';
else if($type == 2) $gw_head_title = '베스트셀러';
else if($type == 3) $gw_head_title = '신상품';
else if($type == 4) $gw_head_title = '인기상품';
else if($type == 5) $gw_head_title = '추천상품';
else
    alert('상품유형이 아닙니다.', TW_URL);

include_once("./_head.php");

$sql_search = "";

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by a.{$sort} {$sortodr}, a.index_no desc ";
else
	$sql_order = " order by a.index_no desc ";

$res = query_itemtype($pt_id, $type, $sql_search, $sql_order);
$total_count = sql_num_rows($res);

$mod = 4; // 가로 출력 수
$rows = $page_rows ? (int)$page_rows : ($mod*10);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$result = query_itemtype($pt_id, $type, $sql_search, $sql_order." limit $from_record, $rows ");

include_once($theme_path.'/listtype.skin.php');

include_once("./_tail.php");
?>
<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
	// 실제 번호를 넘김
	$k = $chk[$i];
	
	$gs = sql_fetch("select use_hide from shop_goods where index_no = '{$_POST['gs_id'][$k]}' "); 

	$gs['use_hide'] = preg_replace('/\s+/', '', $gs['use_hide']);

	$s_value = "";

	// 선택상품감춤
	if($w == 'hide') {
		$list_data = explode(",", $gs['use_hide']);
		$list_data = array_diff($list_data, array($member['id'])); // 적용 아이디 모두제거
		array_push($list_data, $member['id']);  // 적용아이디 배열에 추가
		$list_data = array_unique($list_data); //중복된 아이디 제거
		$list_data = array_filter($list_data); // 빈 배열 요소를 제거
		$list_data = array_values($list_data); // index 값 주기
		$s_value = implode(",", $list_data);

	} else if($w == 'show') { // 선택상품노출
		$list_data = explode(",", $gs['use_hide']);
		$list_data = array_diff($list_data, array($member['id'])); // 적용 아이디 모두제거
		$list_data = array_unique($list_data); //중복된 아이디 제거			
		$list_data = array_filter($list_data); // 빈 배열 요소를 제거
		$list_data = array_values($list_data); // index 값 주기
		$s_value = implode(",", $list_data);
	}
	
	$sql2 = " update shop_goods set use_hide = '$s_value' where index_no = '{$_POST['gs_id'][$k]}'"; 
	sql_query($sql2);
}

goto_url("./page.php?$q1&page=$page");
?>
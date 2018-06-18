<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$gubun = trim($_POST['gubun']);
$point = trim($_POST['point']);

// 포인트 비율 값 체크
if($point > 99)
	alert("포인트 비율을 0과 99 사이의 값으로 입력해 주십시오.");

if($gubun == '1') // 선택한 상품만 적용 
{
	for($i=0; $i<count($_POST['chk']); $i++) 
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gs_id = $_POST['gs_id'][$k];

		$sql = " select account from shop_goods where index_no = '$gs_id' ";
		$gs = sql_fetch($sql);	

		$gpoint = round($gs['account'] * $point/100);
	
		$sql = " update shop_goods
					set gpoint = '$gpoint'
				  where index_no = '$gs_id' ";
		sql_query($sql);
	}
} 
else if($gubun == '2') // 검색된 상품에 적용 
{ 
	foreach(explode("&", $q1) as $arr_param) {
		$param = explode("=", $arr_param);
		$$param[0] = $param[1];
	}

	if($sel_ca1) $sca = $sel_ca1;
	if($sel_ca2) $sca = $sel_ca2;
	if($sel_ca3) $sca = $sel_ca3;
	if($sel_ca4) $sca = $sel_ca4;
	if($sel_ca5) $sca = $sel_ca5;

	$sql_common = " from shop_goods a ";
	$sql_search = " where a.use_aff = 0 and a.shop_state = 0 ";
	$sql_order  = " group by a.index_no order by a.index_no desc ";

	include_once(TW_ADMIN_PATH.'/goods/goods_query.inc.php');	

	$sql = " select a.index_no,a.account $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$gpoint = round($row['account'] * $point/100);
	
		$sql = " update shop_goods
					set gpoint = '$gpoint'
				  where index_no = '{$row['index_no']}' ";
		sql_query($sql);
	}	
}

goto_url("../goods.php?$q1&page=$page");
?>
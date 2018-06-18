<?php
define('_PURENESS_', true);
include_once("./_common.php");

if($mode == 'w') {
	$target_table = 'shop_cate_'.$mb_id;

	$ca = sql_fetch("select * from $target_table where index_no = '$ca_no' "); 
	if($ca['p_hide'] == '0')
		$s_value = '1';
	else
		$s_value = '0';

	$len = strlen($ca['catecode']);
	$sql_where = " where SUBSTRING(catecode,1,$len) = '{$ca['catecode']}' ";

	$sql = "update {$target_table} set p_hide='$s_value' {$sql_where} ";
	$res = sql_query($sql);

	if($res) 
		die("{\"error\":\"처리 되었습니다.\"}"); // 정상
	else 
		die("{\"error\":\"일시적인 오류\"}");
}
?>
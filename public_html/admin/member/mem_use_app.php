<?php
define('_PURENESS_', true);
include_once("./_common.php");

if($mode == 'u') {
	$mb = get_member($mb_id, 'use_app');
	if($mb['use_app'] == '0')
		$f_value = '1';
	else
		$f_value = '0';

	$sql = " update shop_member set use_app='$f_value' where id='$mb_id' ";
	$r = sql_query($sql);

	if($r) 
		die("{\"error\":\"\"}"); // 정상
	else 
		die("{\"error\":\"일시적인 오류\"}");
}
?>
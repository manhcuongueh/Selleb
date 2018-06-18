<?php
include_once("./_common.php");

if($_POST['enc_field'] && ENC_FIELD == $_POST['enc_field']) {	
	get_sql_search($ss_tx, $pt_id);
	goto_url(TW_SHOP_URL."/search.php?ss_tx=".urlencode($ss_tx));
} else {
	alert("잘못된 접근 입니다.");
	exit;
}
?>
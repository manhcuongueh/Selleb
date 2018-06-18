<?php
include_once("./_common.php");

$gw_head_title = '대량구매 문의';
include_once(TW_PATH."/head.sub.php");

$gs = get_goods($gs_id);
$mb_id = $gs['mb_id'];

$row = sql_fetch(" select * from shop_seller where sup_code = '$mb_id' ");
if($row['index_no']) {
	$mb = get_member($row['mb_id']);
	$mb_id = $mb['id'];
}

$seller = get_member($mb_id);

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TW_SHOP_URL.'/purch_update.php';

include_once($theme_path.'/purch.skin.php');

include_once(TW_PATH."/tail.sub.php");
?>
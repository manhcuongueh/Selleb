<?php
include_once("./_common.php");
//include_once(TW_SHOP_PATH.'/settle_naverpay.inc.php');

$gw_head_title = '장바구니';
include_once("./_head.php");

$sql = " select * 
		   from shop_cart 
		  where mb_no = '$mb_no' 
		    and ct_select = '0' 
		  group by gs_id 
		  order by index_no ";
$result = sql_query($sql);
$cart_count = sql_num_rows($result);

$cart_action_url = TW_SHOP_URL.'/cartupdate.php';

include_once($theme_path.'/cart.skin.php');

include_once("./_tail.php");
?>
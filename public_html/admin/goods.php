<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU5;
$pg_num = 5;
$snb_icon = "<i class=\"ionicons ion-bag\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "list") $pg_title2 = ADMIN_MENU5_1;
if($code == "type") $pg_title2 = ADMIN_MENU5_2;
if(in_array($code, array('brand','brand_form'))) $pg_title2 = ADMIN_MENU5_10;
if(in_array($code, array('plan','plan_form'))) $pg_title2 = ADMIN_MENU5_21;
if($code == "price") $pg_title2 = ADMIN_MENU5_19;
if($code == "stock") $pg_title2 = ADMIN_MENU5_6;
if($code == "optstock") $pg_title2 = ADMIN_MENU5_7;
if(in_array($code, array('xls_reg','xls_reg_update'))) $pg_title2 = ADMIN_MENU5_11;
if(in_array($code, array('xls_option_reg','xls_option_reg_update'))) $pg_title2 = ADMIN_MENU5_20;
if(in_array($code, array('xls_mod','xls_mod_update'))) $pg_title2 = ADMIN_MENU5_12;
if($code == "getprice") $pg_title2 = ADMIN_MENU5_4;
if($code == "getpoint") $pg_title2 = ADMIN_MENU5_22;
if($code == "getuse") $pg_title2 = ADMIN_MENU5_23;
if($code == "getmove") $pg_title2 = ADMIN_MENU5_24;
if($code == "getbrand") $pg_title2 = ADMIN_MENU5_25;
if($code == "getdelivery") $pg_title2 = ADMIN_MENU5_26;
if($code == "getbuylevel") $pg_title2 = ADMIN_MENU5_27;
if($code == "supply") $pg_title2 = ADMIN_MENU5_13;
if($code == "userlist") $pg_title2 = ADMIN_MENU5_14;
if(in_array($code, array('qa','qa_form'))) $pg_title2 = ADMIN_MENU5_15;
if($code == "review") $pg_title2 = ADMIN_MENU5_16;
if(in_array($code, array('gift','gift_form'))) $pg_title2 = ADMIN_MENU5_17;
if(in_array($code, array('coupon','coupon_form'))) $pg_title2 = ADMIN_MENU5_18;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once("./goods/goods_{$code}.php");
	?>
</div>

<?php
include_once("admin_tail.php");
?>
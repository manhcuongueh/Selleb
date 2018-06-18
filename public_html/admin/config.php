<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU10;
$pg_num = 10;
$snb_icon = "<i class=\"fa fa-cogs\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "default") $pg_title2 = ADMIN_MENU10_1;
if($code == "mobile") $pg_title2 = ADMIN_MENU10_2;
if($code == "ship") $pg_title2 = ADMIN_MENU10_4;
if($code == "islandlist") $pg_title2 = ADMIN_MENU10_5;
if($code == "sms") $pg_title2 = ADMIN_MENU10_6;
if($code == "pg") $pg_title2 = ADMIN_MENU10_7;
if($code == "supply") $pg_title2 = ADMIN_MENU10_8;
if($code == "nicecheck") $pg_title2 = ADMIN_MENU10_9;
if($code == "super") $pg_title2 = ADMIN_MENU10_10;
if(in_array($code, array('board_group','board_group_form'))) $pg_title2 = ADMIN_MENU10_11;
if(in_array($code, array('board','board_form'))) $pg_title2 = ADMIN_MENU10_12;
if($code == "keyword") $pg_title2 = ADMIN_MENU10_13;
if(in_array($code, array('popup','popup_form'))) $pg_title2 = ADMIN_MENU10_14;
if($code == "sns") $pg_title2 = ADMIN_MENU10_15;
if($code == "meta") $pg_title2 = ADMIN_MENU10_16;
if($code == "register") $pg_title2 = ADMIN_MENU10_17;
if($code == "sendmail_test") $pg_title2 = ADMIN_MENU10_18;
if($code == "kakaopay") $pg_title2 = ADMIN_MENU10_19;
if($code == "naverpay") $pg_title2 = ADMIN_MENU10_20;
if($code == "ipaccess") $pg_title2 = ADMIN_MENU10_21;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once("./config/{$code}.php");
	?>
</div>

<?php
include_once("admin_tail.php");
?>
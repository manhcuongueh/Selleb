<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU2;
$pg_num = 2;
$snb_icon = "<i class=\"fa fa-handshake-o\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "regis") $pg_title2 = ADMIN_MENU2_1;
if($code == "money") $pg_title2 = ADMIN_MENU2_2;
if($code == "level") $pg_title2 = ADMIN_MENU2_3;
if($code == "sin") $pg_title2 = ADMIN_MENU2_4;
if($code == "member") $pg_title2 = ADMIN_MENU2_5;
if($code == "pay_exp") $pg_title2 = ADMIN_MENU2_6;
if($code == "pay") $pg_title2 = ADMIN_MENU2_7;
if($code == "pay_real") $pg_title2 = ADMIN_MENU2_8;
if($code == "pay_log") $pg_title2 = ADMIN_MENU2_9;
if($code == "record") $pg_title2 = ADMIN_MENU2_10;
if($code == "pay_goods") $pg_title2 = ADMIN_MENU2_11;
if($code == "leave") $pg_title2 = ADMIN_MENU2_12;
if($code == "tree") $pg_title2 = ADMIN_MENU2_13;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once("./partner/pt_{$code}.php");
	?>
</div>

<?php 
include_once("admin_tail.php");
?>
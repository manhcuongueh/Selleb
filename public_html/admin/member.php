<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU1;
$pg_num = 1;
$snb_icon = "<i class=\"ionicons ion-ios-people fs40\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "list") $pg_title2 = ADMIN_MENU1_1;
if($code == "level_form") $pg_title2 = ADMIN_MENU1_2;
if($code == "register_form") $pg_title2 = ADMIN_MENU1_3;
if(in_array($code, array('xls','xls_update'))) $pg_title2 = ADMIN_MENU1_4;
if($code == "month") $pg_title2 = ADMIN_MENU1_5;
if($code == "day") $pg_title2 = ADMIN_MENU1_6;
if($code == "point") $pg_title2 = ADMIN_MENU1_7;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once("./member/mem_{$code}.php");
	?>
</div>

<?php 
include_once("admin_tail.php");
?>